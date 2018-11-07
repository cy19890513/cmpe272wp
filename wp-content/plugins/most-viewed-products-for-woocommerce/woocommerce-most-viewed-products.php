<?php

/**
 * Plugin Name:       Most Viewed Products for WooCommerce
 * Plugin URI:        
 * Description:       Display a list of most viewed wooCommerce products in Admin and on the frontend.
 * Version:           1.1.0
 * Author:            Ptech Software
 * Author URI:        
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt     
 * Text Domain:       woocommerce-most-viewed-products
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

define ( 'ZWMVP_TXTDOMAIN', 'woocommerce-most-viewed-products' );

/**
 * Show admin notice & de-activate itself if WooCommerce plugin is not active.
 */
if ( ! function_exists( 'zwcmvp_has_parent_plugin' ) ) {
	function zwcmvp_has_parent_plugin() {
		if ( is_admin() && ( ! class_exists( 'WooCommerce' ) && current_user_can( 'activate_plugins' ) ) ) {
			add_action( 'admin_notices', create_function( null, 'echo \'<div class="error"><p>\' . sprintf( __( \'Notice: <strong>WooCommerce</strong> must be activated to use the <strong>WooCommerce Most Viewed Products</strong> plugin. %sVisit your plugins page to install and activate.\', \'woo-most-viewed-products\' ), \'<a href="\' . admin_url( \'plugins.php#woocommerce\' ) . \'">\' ) . \'</a></p></div>\';' ) );

			deactivate_plugins( plugin_basename( __FILE__ ) );

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}
	}
}

add_action( 'admin_init', 'zwcmvp_has_parent_plugin' );


/**
 * Admin setting menu page invoking
 */
function zwcmvp_admin_settings_page() {
	if ( is_user_logged_in() ) {
		if ( current_user_can( 'administrator' ) ) {
			$parent_slug= 'woocommerce';
			$page_title = __( 'Most Viewed Products', ZWMVP_TXTDOMAIN );
			$menu_title = __( 'Most Viewed Products', ZWMVP_TXTDOMAIN );
			$capability = 'manage_woocommerce';
			$menu_slug 	= 'zwcmvp-product-views';
			$callable 	=  'zwcmvp_admin_init';
			add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callable );
		}
	}
}

/**
 * Admin setting html generation start.
 */
function zwcmvp_admin_init() {
	if ( isset( $_GET['action'] ) ) {
	    $action = $_GET['action'];
	} else {			
	   $action = '';
	   $active_tab = "nav-tab-active";
	}
	if( $action == 'view-products' || $action == 'setting' ) {
		$active_tab  = "nav-tab-active";
	}  
	$adminMostViewProduct = zwcmvp_get_most_viewed_products( 5 ); ?>
	<div class="wrap">    
    	<h2><?php _e( 'WooCommerce - Most Viewed Products', ZWMVP_TXTDOMAIN ); ?></h2>
    	<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
    		<a href="admin.php?page=zwcmvp-product-views&action=view-products" class="nav-tab <?php if (isset($active_tab)) echo $active_tab; ?>"> <?php _e( 'Most Viewed Products', ZWMVP_TXTDOMAIN );?> </a>
    		<a href="admin.php?page=zwcmvp-product-views&action=setting" class="nav-tab <?php if (isset($active_tab)) echo $active_tab; ?>"> <?php _e( 'Settings', ZWMVP_TXTDOMAIN );?> </a>
    	</h2>
    	<?php if( $action == 'view-products' || $action == ''  ) { ?>
        <form id="zwcmvp-most-view-products" method="get" >
            <input type="hidden" name="page" value="zwcmvp-product-views" />
            <input type="hidden" name="action" value="view-products" />
            <?php
        		global $wpdb;
                include_once( 'includes/classes/class-zwcmvp-orders-table.php' );
                $data = zwcmvp_get_most_viewed_products();
                $wcal_abandoned_order_list = new ZWCMVP_Orders_table();
                $wcal_abandoned_order_list->prepare_items();
            	$wcal_abandoned_order_list->display(); ?>
        </form>
        <?php } 
        
        if( $action == 'setting' ) { 
        	$options = get_option('zwmvp_options');
			if (isset($_POST['form_submit']))
			    {
			    
			    // if( $options['no_of_item_per_page'] > $_POST['no_of_view_posts'] )  {
			    // 	$options['no_of_item_per_page'] = sanitize_key($_POST['no_of_view_posts']);
			    // }  else {
			    // 	$options['no_of_item_per_page'] = sanitize_key($_POST['no_of_item_per_page']);
			    // }   
			    $options['no_of_view_posts'] = sanitize_key($_POST['no_of_view_posts']);

			    ?><div class="updated fade"><p><?php _e('Settings Saved', ZWMVP_TXTDOMAIN) ?></p></div><?php

			    update_option('zwmvp_options', $options);   
			} ?> 
        	<form id="zwcmvp-most-view-products-setting" name="setting" action="admin.php?page=zwcmvp-product-views&action=setting" method="post" >
	        	<table>
	        		<tbody>
		        	<tr valign="top">
						<th scope="row">
							<label for="highlight_color"><?php _e('Total Number of items View',ZWMVP_TXTDOMAIN); ?>:</label>
						</th>
						<td>
							<input type="number" min="0" id="no_of_view_posts" name="no_of_view_posts" value="<?php if(isset($options['no_of_view_posts']) && !empty($options['no_of_view_posts'])) { echo $options['no_of_view_posts']; } else { echo "-1"; } ?>" /><br/>
						</td>
					</tr>
					<!-- <tr valign="top">
						<th scope="row">
							<label for="highlight_color"><?php _e('Number of items per page:',ZWMVP_TXTDOMAIN); ?>:</label>
						</th>
						<td>
							<input type="number" min="0" id="no_of_item_per_page" name="no_of_item_per_page" value="<?php echo $options['no_of_item_per_page'];?>" /><br/>
						</td>
					</tr> -->
					
					</tbody>
				</table>
				<div class="se-submit">
					<input type="hidden" name="form_submit" value="true" />
					<input type="submit" class="button button-primary" value="<?php _e('Save Settings', ZWMVP_TXTDOMAIN ); ?>" />
				</div>
			</form>
        <?php } ?>
    </div>  
<?php } 


/**
 * Get the WP_Query instance for most viewed products
 */
function zwcmvp_get_most_viewed_products() {

	$options = get_option('zwmvp_options');
	if(isset($options['no_of_view_posts']) && !empty($options['no_of_view_posts'])){
        $total_items = $options['no_of_view_posts'];
  	} else {
  		$total_items = -1;
  	}
	$count_key                = 'zwcmvp_product_view_count';
	$query_args               = array(
		'no_found_rows'  => 1,
		'posts_per_page'  => $total_items,
		'post_status'    => 'publish',
		'post_type'      => 'product',
		'orderby'        => 'meta_value',
		'order'          => 'DESC',
		'meta_key'       => $count_key,
	);
	$query_args['meta_query'] = array(
		array(
			'key'     => $count_key,
			'value'   => '0',
			'type'    => 'numeric',
			'compare' => '>',
		),
	);
	$zwcmvp_query              = new WP_Query( $query_args );
	$viewProductData = array();
	if ( $zwcmvp_query->have_posts() ) {
		while ( $zwcmvp_query->have_posts() ) {
			$zwcmvp_query->the_post();
			global $product; 
			$viewProductData[] = array(
				'id' => '<a href="'.esc_url( get_permalink( $product->get_id() ) ).'" title="'.esc_attr( $product->get_title() ).'">'.$product->get_id().'</a>' ,
			    'product_name' => $product->get_title(),
			    'view_count' => zwcmvp_get_view_count( $product->get_id() ),
			    'amount' => $product->get_price_html()
			);

		}
	} 
	return $viewProductData;
}


/**
 * Get the view count for a particular product
 */
function zwcmvp_get_view_count( $post_id ) {
	$count_key = 'zwcmvp_product_view_count';
	$count     = get_post_meta( $post_id, $count_key, true );
	if ( empty( $count ) ) {
		delete_post_meta( $post_id, $count_key );
		add_post_meta( $post_id, $count_key, '0' );
		$count = '0';
	}

	return $count;
}


/**
 * Set view count for a product
 */
function zwcmvp_set_view_count( $post_id ) {
	$count_key = 'zwcmvp_product_view_count';
	$count     = get_post_meta( $post_id, $count_key, true );
	if ( $count == '' ) {
		delete_post_meta( $post_id, $count_key );
		update_post_meta( $post_id, $count_key, '1' );
	} else {
		$count ++;
		update_post_meta( $post_id, $count_key, (string) $count );
	}
}

/**
 * Set view counts for all products once viewed
 */
function zwcmvp_set_view_count_products() {
	global $product;
	zwcmvp_set_view_count( $product->get_id() );
}


/**
 * Shortcode
 *
 * @param $atts attributes
 *
 * @return string rendered products output
 *
 * @since 1.0.0
 */
function zwcmvp_fronend_most_viewed_products_shortcode( $atts ) {

	$atts = shortcode_atts(
		array(
			'limit' => '10',
		),
		$atts
	);
	$content = zwcmvp_render_most_viewed_products( $atts['limit'] );

	return $content;
}
/**
 * Register the widget "Most Viewed Products"
 *
 * @since 1.0.0
 */
function zwcmvp_register_widgets() {
	register_widget( 'ZWCMVP_Widget_Most_Viewed' );
}

function zwcmvp_get_view_count_html( $product_id = 0 ) {
	if ( empty( $product_id ) ) {
		return '';
	}
	$view_count      = zwcmvp_get_view_count( $product_id );
	$view_count_html = '<span class="product-views">' . $view_count . ' ' . __( 'Views', 'woo-most-viewed-products' ) . '  </span>';

	return apply_filters( 'zwcmvp_view_count_html', $view_count_html, $product_id, $view_count );
}
/**
 * @param int $num_posts
 *
 * @return string
 *
 * @since 1.0.0
 */
function zwcmvp_render_most_viewed_products( $num_posts = 10 ) {
	$r = zwcmvp_get_most_viewed_products_query( $num_posts );

	ob_start();
	if ( $r->have_posts() ) {
		echo '<ul class="woo-most-viewed product_list_widget">';
		while ( $r->have_posts() ) {
			$r->the_post();
			global $product; ?>
			<li>
				<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>"
				   title="<?php echo esc_attr( $product->get_title() ); ?>">
					<?php echo $product->get_image(); ?>
					<span class="product-title"><?php echo $product->get_title(); ?></span>
				</a>
				<?php echo zwcmvp_get_view_count_html( $product->get_id() ); ?>
				<?php echo $product->get_price_html(); ?>
			</li>
			<?php
		}
		echo '</ul>';
	} else {
		echo '<ul class="woo-most-viewed zwcmvp-not-found product_list_widget">';
		echo '<li>' . __( 'No products have been viewed yet !!', 'woo-most-viewed-products' ) . '</li>';
		echo '</ul>';
	}
	wp_reset_postdata();
	$content = ob_get_clean();

	return $content;
}
function zwcmvp_get_most_viewed_products_query( $num_posts = 10 ) {
	$count_key                = 'zwcmvp_product_view_count';
	$query_args               = array(
		'posts_per_page' => $num_posts,
		'no_found_rows'  => 1,
		'post_status'    => 'publish',
		'post_type'      => 'product',
		'orderby'        => 'meta_value_num',
		'order'          => 'DESC',
		'meta_key'       => $count_key,
	);
	$query_args['meta_query'] = array(
		array(
			'key'     => $count_key,
			'value'   => '0',
			'type'    => 'numeric',
			'compare' => '>',
		),
	);
	$zwcmvp_query              = new WP_Query( $query_args );

	return $zwcmvp_query;
}



/**
 * Load plugin functionalities
 *
 * @since 1.0.0
 */
function zwcmvp_plugin_load() {

	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	require_once( 'woocommerce-most-viewed-products-widget.php' );

	add_shortcode( 'zwcmvp', 'zwcmvp_fronend_most_viewed_products_shortcode' );

	add_action( 'woocommerce_after_single_product', 'zwcmvp_set_view_count_products' );

	/**
	 * Creates admin menu pages.
	 */
	add_action( 'admin_menu', 'zwcmvp_admin_settings_page' );
	add_action( 'widgets_init', 'zwcmvp_register_widgets' );
}

add_action( 'plugins_loaded', 'zwcmvp_plugin_load' );

