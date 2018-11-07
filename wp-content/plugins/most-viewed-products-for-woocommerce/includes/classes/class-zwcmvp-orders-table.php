<?php 
// Load WP_List_Table if not loaded
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class ZWCMVP_Orders_table extends WP_List_Table {

	/**
	 * Number of results to show per page
	 *
	 * @var string
	 * @since 2.5.2
	 */
	public $per_page = 10;

	/**
	 * URL of this page
	 *
	 * @var string
	 * @since 2.5.2
	 */
	public $base_url;

	/**
	 * Total number of bookings
	 *
	 * @var int
	 * @since 2.5.2
	 */
	public $total_count;

    /**
	 * Get things started
	 *
	 * @see WP_List_Table::__construct()
	 */
	public function __construct() {
		global $status, $page;
		// Set parent defaults
		parent::__construct( array(
		        'singular' => __( 'abandoned_order_id', 'woocommerce-ac' ), //singular name of the listed records
		        'plural'   => __( 'abandoned_order_ids', 'woocommerce-ac' ), //plural name of the listed records
				'ajax'      => false             			// Does this table support ajax?
		) );
		$this->process_bulk_action();
        $this->base_url = admin_url( 'admin.php?page=zwcmvp-product-views' );
	}
	
	

	function get_columns(){
	  $columns = array(
	    'id' => 'ID',
	    'product_name'    => 'Product Name',
	    'view_count'      => 'View Count',
	    'amount' => 'Amount'
	  );
	  return $columns;
	}

	function prepare_items() {
		  $data = zwcmvp_get_most_viewed_products();
		  $options = get_option('zwmvp_options');
          $per_page = 10;
		  $columns = $this->get_columns();
		  $hidden = array();
		  $sortable = $this->get_sortable_columns();
		  $this->_column_headers = array($columns, $hidden, $sortable);

		  $current_page = $this->get_pagenum();
		  $total_items = count($data);	

	 	  usort( $data, array( &$this, 'usort_reorder' ) );

		  // only ncessary because we have sample data
		  $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
		  $this->items = $data;
		  $this->set_pagination_args( array(
		    'total_items' => $total_items,                  //WE have to calculate the total number of items
		    'per_page'    => $per_page,
		    'total_pages' => ceil( $total_items / $per_page )                     //WE have to determine how many items to show on a page
		  ) );
	 
	}

	function column_default( $item, $column_name ) {
	  switch( $column_name ) { 
	    case 'id':
	    case 'product_name':
	    case 'view_count':
	    case 'amount':
	      return $item[ $column_name ];
	    default:
	      return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
	  }
	}

	function get_sortable_columns() {
	  $sortable_columns = array(
	    'product_name'  => array('product_name',false),
	    'view_count' => array('view_count',false),
	    'amount'   => array('amount',false)
	  );
	  return $sortable_columns;
	}

	// function get_bulk_actions() {
	//   $actions = array(
	//     'delete'    => 'Delete'
	//   );
	//   return $actions;
	// }
	function usort_reorder( $a, $b ) {
	  // If no sort, default to title
	  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'view_count';
	  // If no order, default to asc
	  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'desc';
	  // Determine sort order
	  $result = strnatcasecmp( $a[$orderby], $b[$orderby] );
	  // Send final sort direction to usort
	  return ( $order === 'asc' ) ? $result : -$result;
	}

}