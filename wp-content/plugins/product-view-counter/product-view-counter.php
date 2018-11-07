<?php
/**
 * Plugin Name: Product View Counter
 * Plugin URI: https://www.wordpress.org/plugins/product-view-counter
 * Author Name: Aftabul Islam
 * Version: 1.0.0
 * Author Email: toaihimel@gmail.com
 * License: GPLv3
 * Description: The plugin keeps track your product pageviews of today, yesterday, last week and total views.
 * Copyright 2015  Aftabul Islam  (email : toaihimel@gmail.com)
 *
 */

class PVC{

	private $prefix, // Plugin wide prefix
		$path, // Path of the plugin
		$name, // Name of the plugin
		$table // Table Name
		;

	public function __construct(){
		// Prohibiting Direct Access
		defined('ABSPATH') or die(require_once('404.php'));
		$this->prefix = 'product-view-counter';
		$this->name = 'Product View Counter';
		global $wpdb; // global wpdb object
		$this->table = $wpdb->base_prefix.'product_view_counter';
		$this->path = __FILE__;
		register_activation_hook($this->path, array($this, 'activate'));
		register_deactivation_hook($this->path, array($this, 'deactivate'));
		register_uninstall_hook('uninstall.php', 'product_view_counter_uninstall');
		// Loading assets
		add_action('wp_enqueue_scripts', array($this, 'assets'));
		add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
		add_action('admin_menu', array($this, 'menu'));
		add_action('woocommerce_after_single_product', array($this, 'view_counter_display'));
	}

	public function view_counter_display(){
		global $product, $wpdb;
		$table = $this->table;

		// Record insertion query
		$sql = "INSERT INTO $table(product_id) VALUES($product->id);";
		if(get_option($this->__('count-admin')) == 'on'){
			$wpdb->get_results($sql, OBJECT);
		} else if(get_option($this->__('count-admin')) == 'off'){
			if(!current_user_can('manage_options')) $wpdb->get_results($sql, OBJECT);
		}

		if(get_option($this->__('available-to-all')) == 'off')
			if(!current_user_can('manage_options')) return;

		// Total View
		$sql = "SELECT COUNT(id) FROM $table;";
		$total = $wpdb->get_var($sql);

		// Todays View
		$sql = "SELECT COUNT(id) FROM $table WHERE DATE($table._time) = CURDATE();";
		$today = $wpdb->get_var($sql);

		// Yesterdays View
		$sql = "SELECT COUNT(id) FROM $table WHERE DATE($table._time) = CURDATE() - INTERVAL 1 DAY;";
		$yesterday = $wpdb->get_var($sql);

		// Last Week Excluding Today and Yesterday
		$sql = "SELECT COUNT(id) FROM $table WHERE DATE($table._time) <= CURDATE() - INTERVAL 2 DAY AND DATE($table._time) >= CURDATE() - INTERVAL 9 DAY ;";
		$last_week = $wpdb->get_var($sql);

		// Includeing the view template
		require_once('view.php');

	}

	// Activation Function
	public function activate(){
		if(!class_exists('Woocommerce')) deactivate_plugins($this->path);
		$this->create_database();
		add_option($this->__('count-admin'), 'off', $deprecated = '', $atuoload = 'yes');
		add_option($this->__('available-to-all'), 'off', $deprecated = '', $atuoload = 'yes');
	}

	// Deactivation Function
	public function deactivate(){
		delete_option($this->__('count-admin'));
		delete_option($this->__('available-to-all'));
	}

	// Adds Admin Menu
	public function menu(){
		add_submenu_page('woocommerce', 'Product View Counter', 'Product View Counter', 'manage_options', 'product-view-counter', array($this, 'admin_control_form'));
	}

	// Creates The Necessary Database
	private function create_database(){
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $this->table(
		  id int(11) NOT NULL AUTO_INCREMENT,
		  product_id int(11) DEFAULT '0' NOT NULL,
		  _time timestamp DEFAULT NOW() NOT NULL,
		  UNIQUE KEY id (id)
		  ) $charset_collate;";
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	// Saving Admin Form Data
	public function save_admin_form_data(){
		$post = $_POST;
		// Out of stock message
		if(!empty($post) && (!empty($post[$this->__('count-admin')])  || !empty($post[$this->__('available-to-all')]) ) ) {

			if(!empty($post[$this->__('count-admin')])) update_option($this->__('count-admin'), $post[$this->__('count-admin')]);
				else update_option($this->__('count-admin'), 'off');

			if(!empty($post[$this->__('available-to-all')])) update_option($this->__('available-to-all'), $post[$this->__('available-to-all')]);
				else update_option($this->__('available-to-all'), 'off');

			// Success Message
			echo '<div class="alert alert-success" role="alert">Saved Successfully</div>';
		}
	}

	// Admin Controll Form
	public function admin_control_form(){

		if(!current_user_can('manage_options')) wp_die(__('You don\'t have permission to access this page'));

		require_once('admin-contorl-form.php');


	}

	public function assets(){
		wp_enqueue_style($this->__('style'), $this->url('css/style.css'), false);
	}

	public function admin_assets(){
		wp_enqueue_script('jquery');
		wp_enqueue_style($this->__('bootstrap_css'), $this->url('bootstrap-3.3.5/css/bootstrap.min.css'), false);
		wp_enqueue_script($this->__('bootstrap_js'), $this->url('bootstrap-3.3.5/css/bootstrap.min.js'), false);
	}

	// Prefixes all the option names
	private function __($string){return $string = $this->prefix.'__'.$string;}

	// Absolute file path inside the plugin
	private function url($string){return plugins_url('/'.$this->prefix.'/'.$string);}

}
$pvc = new PVC();

