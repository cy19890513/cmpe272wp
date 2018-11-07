<?php

function product_view_counter_uninstall(){
	// Cheackes if the file is called by wordpress
	defined('WP_UNINSTALL_PLUGIN') or die(require_once('404.php'));
	global $wpdb;
	// Deleting the database
	$wpdb->query( "DROP TABLE IF EXISTS $wpdb->base_prefix.product_view_counter;" );
}
