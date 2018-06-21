<?php

add_action('admin_menu', 'wk_query');

function wk_query() {	
  
  $hook = add_submenu_page('edit.php?post_type=wk_knowledgebase','Knowledgebase Query','Query list','manage_options','query-detail','query_detail');

  add_submenu_page('edit.php?post_type=wk_knowledgebase','Single Query Detail','Query detail','manage_options','single-query','single_query');

  add_action("load-$hook", 'add_scrn_optn');
}

function add_scrn_optn() {
  $options = 'per_page';
  $args = array(
    'label' => 'Query Per Page',
    'default' => 20,
    'option' =>'query_per_page'
  );
  add_screen_option($options, $args);
}

add_filter('set-screen-option', 'set_options', 10, 3);
function set_options($status, $option, $value) {
  return $value;
}

function query_detail() {
	echo '<div class="wrap">';

	echo '<h3>Queries</h3>';

	if(!class_exists('WP_List_Table')){
    	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
  	}

  	class Query_List_Table extends WP_List_Table {
  		/**
  		* Constructor, we override the parent to pass our own arguments
  		* We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
  		*/

  		function __construct() {
  			parent::__construct( array(
  				'singular' => 'Query',
  				'plural'   => 'Queries',
  				'ajax'     => false
  			) );
  		}

  		function prepare_items() {
  			global $wpdb; 

    		$columns = $this->get_columns();

    		$sortable = $this->get_sortable_columns();

    		$hidden=$this->get_hidden_columns();

    		$this->process_bulk_action();

    		$data = $this->table_data();
           
    		$totalitems = count($data);

    		$user = get_current_user_id();

    		$screen = get_current_screen();    

    		$perpage = $this->get_items_per_page('query_per_page', 20);

    		$this->_column_headers = array($columns,$hidden,$sortable);

    		if ( empty ( $per_page) || $per_page < 1 ) {
           
      		$per_page = $screen->get_option( 'per_page', 'default' );

    		}
    		function usort_reorder($a,$b) {
  			$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'name'; //If no sort, default to title

        	$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc

        	$result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order

        	return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
  			}

    		usort($data, 'usort_reorder');

            $totalpages = ceil($totalitems/$perpage);

            $currentPage = $this->get_pagenum();
               
            $data = array_slice($data,(($currentPage-1)*$perpage),$perpage);

            $this->set_pagination_args( array(

            	"total_items" => $totalitems,

            	"total_pages" => $totalpages,

            	"per_page" => $perpage,
        	) );
               
        	$this->items =$data;
    	}
  		

    	public function get_hidden_columns() {
        	return array(); 
    	}

    	function column_cb($item){

        	return sprintf('<input type="checkbox" id="user_%s"name="user[]" value="%s" />',$item['id'], $item['id']);
    	}

    	function get_columns() {

      		$columns= array(

        		'cb'         => '<input type="checkbox" />', //Render a checkbox instead of text

        		'name'=>__('Name'),

        		'email'      => __('Email'), 

        		'subject'  =>__('Subject'),

        		'query'    =>__('Query'),
       		

      		);

      		return $columns;
    	}

    	/**

    	* Decide which columns to activate the sorting functionality on

    	* @return array $sortable, the array of columns that can be sorted by the user

    	*/

    	public function get_sortable_columns() {

      		$sortable_columns = array(
            	'name'     => array('name',true),        
      		);
      		return $sortable_columns;
    	}

    	private function table_data() {
    		global $wpdb;

    		$table_name = $wpdb->prefix.'knw_queries';

    		$data = array();

    		if (isset($_GET['s'])) {
    			$search = $_GET['s'];

    			$search = trim($search);

    			$wk_post = $wpdb->get_results("SELECT id,user_name,user_email,query_subject,query FROM $table_name WHERE user_name LIKE '%$search%' AND post_status='publish'");
    		}
    		else {
    			$wk_post = $wpdb->get_results("SELECT id,user_name,user_email,query_subject,query FROM $table_name WHERE post_status='publish'");
    		}

    		$id = array();

    		$name = array();    		

    		$email = array();    		

    		$subject = array();

    		$query = array();

    		    		

    		$i = 0;
    		foreach ($wk_post as $posts) {
    			$id[] = $posts->id;

    			$name[] = $posts->user_name;    			

    			$email[] = $posts->user_email;

    			$subject[] = $posts->query_subject;

    			$query[] = $posts->query;

    			$data[] = array(
    				'id' => $id[$i],

    				'name' =>$name[$i],

    				'email' =>$email[$i],

    				'subject' =>$subject[$i],

    				'query'  =>$query[$i]
    			);
    			$i++;
    		}
    		return $data;

    	}

    	function get_bulk_actions()
    	{

      		$actions = array(

          		'trash'    => 'Move To Trash'            
      		);

      		return $actions;
    	}



    	public function process_bulk_action()

    	{ 
      		global $wpdb;

      		if ('trash' === $this->current_action()) {

        		if (isset($_GET['user'])) {

          			if (is_array($_GET['user'])){

            			foreach ($_GET['user'] as $id) {

            				if(!empty($id)) {              

              					$table_name = $wpdb->prefix . 'knw_queries';
           
              					$wpdb->query("update $table_name set post_status='trash' WHERE id IN($id)"); 

            				}

          				}

        			}

        			else{

            			if (!empty($_GET['user'])) {
                     
              				$id=$_GET['user'];                       

              				$table_name = $wpdb->prefix . 'knw_queries';
                   
              				$wpdb->query("update $table_name set post_status='trash' WHERE id =$id"); 

            			}

        			}
      			}
    		}
  		}

  		function column_default($item, $column_name) {
    		switch( $column_name ) {
      			case 'name': 
      			case 'email': 
      			case 'subject':
      			case 'query':      			
        			return $item[ $column_name ];
      			default:
        			return print_r($item, true);
    		}
  		}

  		function column_name($item) {
    		$actions = array(
      			'edit'     => sprintf('<a href="edit.php?post_type=wk_knowledgebase&page=single-query&user=%s">View</a>', $item['id']),         
      			'trash'    => sprintf('<a href="edit.php?post_type=wk_knowledgebase&page=query-detail&action=trash&user=%s">Trash</a>',$item['id'])      
    		);

    		return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions) );
  		}
	}

  	$wp_list_table = new Query_List_Table(); 

  	if( isset($_GET['s']) ){

    	$wp_list_table->prepare_items($_GET['s']);

  	} else {

    	$wp_list_table->prepare_items();

  	}
 
?>

 <form method="GET">

    <input type="hidden" name="post_type" value="wk_knowledgebase" />
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />

  <?php $wp_list_table->search_box('Search', 'search-id');

    $wp_list_table->display();

 ?>

</form>
<?php  	
}

function single_query() {
  if (isset($_GET['user'])) {
    $id = $_GET['user'];

    global $wpdb;

    $table_name = $wpdb->prefix.'knw_queries';

    $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id = $id");

    $data = array();

    foreach ($result as $res) {
    ?>
    <div class="wk_knw_query wrap">
     <h1>Query Detail</h1>

    <label for="user-name">Name</label>

    <input type="text" id="user-name" value="<?php echo $res->user_name; ?>" readonly><br><br>

    <label for="email">Email</label>

    <input type="text" id="email" value="<?php echo $res->user_email; ?>" readonly><br><br>

    <label for="sbjct">Subject</label>

    <input type="text" id="sbjct" value="<?php echo $res->query_subject; ?>" readonly><br><br>

    <label for="query">Query</label>

    <textarea id="query" rows="8" readonly><?php echo $res->query; ?></textarea>

    </div>      
     <?php
    }
  }
  else {
    echo "<div class='drct-access'>";
    echo "<h1>Please Select Query First. <a href='edit.php?post_type=wk_knowledgebase&page=query-detail'>Follow The Link...</a></h1>";
    echo "</div>";
  }
}

?>