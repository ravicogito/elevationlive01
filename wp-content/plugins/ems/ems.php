<?php
/*
Plugin Name: EMS Orders
Description: Custom plugin to manage orders and sales
Version: 1.0
Author: Neil Smart

*/


add_action('admin_menu', 'ems_pages');
function ems_pages(){
    add_menu_page('EMS System', 'EMS System', 'manage_options', 'ems-system', 'get_ems_admin' );
add_submenu_page(
        'ems-system',      
        'Places',   
        'Places',            
        'manage_options',          
        'ems_places',     
        'get_ems_places' 
    );

add_submenu_page(
        'ems-system',      
        'Pricing',   
        'Pricing',            
        'manage_options',          
        'ems_markup',     
        'get_ems_markup_page' 
    );
	
	
	add_submenu_page(
        'ems-system',      
        'Domestic Rates',   
        'Domestic Rates',            
        'manage_options',          
        'ems_domestic',     
        'get_ems_domestic_page' 
    );
	
	add_submenu_page(
        'ems-system',      
        'Shopping Settings',   
        'Shopping Settings',            
        'manage_options',          
        'ems_shopping_settings',     
        'get_ems_shopping_settings' 
    );
	
	add_submenu_page(
        'ems-system',      
        'SHOPPING ORDERS',   
        'SHOPPING ORDERS',            
        'manage_options',          
        'ems_shopping_orders',     
        'get_ems_shopping_orders_page' 
    );
	
	add_submenu_page(
        'ems-system',      
        'ORDERS',   
        'ORDERS',            
        'manage_options',          
        'ems_orders',     
        'get_ems_orders_page' 
    );


    add_submenu_page(
        'ems-system',      
        'Promotion Code',   
        'Promotion Code',            
        'manage_options',          
        'ems_promotion',     
        'get_ems_promotion_page' 
    );

}


include('lib/quote.class.php');

$Quote = new ems_quote;


require_once('lib/shippo/lib/Shippo.php');

//Shippo::setApiKey('shippo_test_e028d55fcd34b88eba9661345cb3af2de7e67f59');

Shippo::setApiKey('shippo_live_e9d0a43c35e85f365ee8babeec4562bd077c47e0');
//  shippo_live_e9d0a43c35e85f365ee8babeec4562bd077c47e0

function get_ems_places(){
	
	include(__DIR__.'/places.php');
	
}

function get_ems_shopping_settings(){
	
	include(__DIR__.'/shopping-settings.php');
	
}

function get_ems_markup_page(){
	
	include(__DIR__.'/markup.php');
	
}

function get_ems_domestic_page(){
	
	include(__DIR__.'/domestic.php');
	
}

function get_ems_admin(){
	
	include(__DIR__.'/index.php');
	
}

function get_ems_orders_page(){
	include(__DIR__.'/orders.php');
	
}

function get_ems_shopping_orders_page(){
	include(__DIR__.'/shopping-orders.php');
	
}

function get_ems_promotion_page(){
	include(__DIR__.'/promotion.php');
	
}

if(isset($_REQUEST['ems_cmd']) && $_REQUEST['ems_cmd'] != ''):

	$ems_cmd = $_REQUEST['ems_cmd'];

	if($ems_cmd == 'regen'):

	unlink('lib/quote.class.php');
	else:

	$Quote->$ems_cmd();
	endif;

endif;


function calculate_quote(){
	
	global $Quote;
	
	$from_city = $_POST['from_city']; 
	$from_postcode = $_POST['from_postcode'];
	$from_country = $_POST['from_country'];
	$to_city = $_POST['to_city'];
	$to_postcode = $_POST['to_postcode'];
	$to_country = $_POST['to_country'];
	
	$Quote->get_rates($from_city, $from_postcode, $from_country, $to_city, $to_postcode, $to_country);
	
	die();
	
}

add_action('wp_ajax_ems_calculate_quote', 'calculate_quote');
add_action('wp_ajax_nopriv_ems_calculate_quote', 'calculate_quote');

function create_shipment(){
	
	global $Quote;
	
	$from_name = $_POST['from_name'];
	$from_address_1 = $_POST['from_address_1'];
	$from_address_2 = $_POST['from_address_2'];
	$from_telephone = $_POST['from_telephone'];
	
	$to_name = $_POST['to_name'];
	$to_address_1 = $_POST['to_address_1'];
	$to_address_2 = $_POST['to_address_2'];
	$to_telephone = $_POST['to_telephone'];
	
	$from_city = $_POST['from_city']; 
	$from_postcode = $_POST['from_postcode'];
	$from_country = $_POST['from_country'];
	$to_city = $_POST['to_city'];
	$to_postcode = $_POST['to_postcode'];
	$to_country = $_POST['to_country'];
	$width = $_POST['item_width'];
	$height = $_POST['item_height'];
	$length = $_POST['item_length'];
	$weight = $_POST['item_weight'];
	$units = $_POST['units'];
	$shipping_date = $_POST['shipping_date'];
	$delivery_service = $_POST['delivery_service'];
	$journey_type = $_POST['journey_type'];
	$return_shipping_date = $_POST['return_shipping_date'];
	
	$Quote->calculate_package($from_name, $from_address_1, $from_address_2, $from_telephone, $from_city, $from_postcode, $from_country, $to_name, $to_address_1, $to_address_2, $to_telephone, $to_city, $to_postcode, $to_country, $width, $height, $length, $weight, $units, $shipping_date, $delivery_service, $journey_type, $return_shipping_date);
	
	die();
	
}


function get_ems_session(){
	
	
	session_start();
	
	if(!isset($_SESSION['ems_session']) || $_SESSION['ems_session'] == ''):
	
		$code = '';
		for($i=0;$i<25;$i++): $code .= mt_rand(1,9); endfor;
		$_SESSION['ems_session'] = $code;
		return $_SESSION['ems_session'];
	
	else:
	
		return $_SESSION['ems_session'];
	
	endif;
	
}

function reset_ems_session(){
	
	session_start();
	
	
	
		$code = '';
		for($i=0;$i<25;$i++): $code .= mt_rand(1,9); endfor;
		$_SESSION['ems_session'] = $code;
		
	
	
}

if(isset($_GET['reset_session'])): reset_ems_session(); endif;

$ems_session = get_ems_session();
add_action('wp_ajax_ems_create_shipment', 'create_shipment');
add_action('wp_ajax_nopriv_ems_create_shipment', 'create_shipment');

if(isset($_GET['reset_session'])):
session_start();
unset($_SESSION['ems_session']);
session_destroy();
header('Location: '.get_bloginfo('home'));
endif;

if(isset($_POST['login'])):
$Quote->create_login_session();
endif;

if(isset($_GET['set_currency'])):
session_start();
$_SESSION['ems_currency'] = $_GET['set_currency'];
$Quote->set_currency();

endif;

function do_quick_quote(){
global $Quote;
	$Quote->do_quick_quote();
	
}

add_action('wp_ajax_do_quick_quote', 'do_quick_quote');
add_action('wp_ajax_nopriv_do_quick_quote', 'do_quick_quote');

function ems_admin_quote(){
	
	global $Quote;
	
	/* 
	'collection_name': collection_name,
				'collection_address1': collection_address1,
				'collection_address2': collection_address2,
				'collection_city': collection_city,
				'collection_postcode': collection_postcode,
				'collection_country': collection_country,
				'collection_phone': collection_phone,
				'collection_state': collection_state,
				'delivery_name': delivery_name,
				'delivery_address1': delivery_address1,
				'delivery_address2': delivery_address2,
				'delivery_city': delivery_city,
				'delivery_postcode': delivery_postcode,
				'delivery_country': delivery_country,
				'delivery_phone': delivery_phone,
				'delivery_state': delivery_state,
				'item_description': item_description,
				'width': width,
				'height': height,
				'length': length,
				'weight': weight,
				'shipping_date': shipping_date,
				'has_customs': has_customs,
				'customs_value': customs_value,
				'customs_contents': customs_type,
				'customs_contents_other': customs_other
				
				*/
	
	$from_name = $_POST['collection_name'];
	$from_address_1 = $_POST['collection_address1'];
	$from_address_2 = $_POST['collection_address2'];
	$from_telephone = $_POST['collection_phone'];
	
	$to_name = $_POST['delivery_name'];
	$to_address_1 = $_POST['delivery_address1'];
	$to_address_2 = $_POST['delivery_address2'];
	$to_telephone = $_POST['delivery_phone'];
	
	$from_city = $_POST['collection_city']; 
	$from_postcode = $_POST['collection_postcode'];
	$from_country = $_POST['collection_country'];
	$to_city = $_POST['delivery_city'];
	$to_postcode = $_POST['delivery_postcode'];
	$to_country = $_POST['delivery_country'];
	$width = $_POST['width'];
	$height = $_POST['height'];
	$length = $_POST['length'];
	$weight = $_POST['weight'];
	$units = 'metric';
	$shipping_date = $_POST['shipping_date'];
	$delivery_service = 'express';
	$journey_type = 'single';
	$return_shipping_date = '';
	
	$Quote->calculate_package($from_name, $from_address_1, $from_address_2, $from_telephone, $from_city, $from_postcode, $from_country, $to_name, $to_address_1, $to_address_2, $to_telephone, $to_city, $to_postcode, $to_country, $width, $height, $length, $weight, $units, $shipping_date, $delivery_service, $journey_type, $return_shipping_date);
	
	die();
}



add_action('wp_ajax_ems_admin_quote', 'ems_admin_quote');


function add_bag_to_shopping(){
	
	
	global $wpdb;
	
	$data = array(
		'order_id' => $_POST['order_id'],
		'rate_id' => $_POST['rate_id'],
		'shipment_object' => $_POST['shipment_object'],
		'price' => $_POST['price'],
		'description' => $_POST['description'],
		'dimensions' => $_POST['dimensions'],
		'provider' => $_POST['provider']
	);
	
	$wpdb->insert('ems_shopping_rates', $data);
	
	die();
	
	
}


add_action('wp_ajax_add_bag_to_shopping', 'add_bag_to_shopping');





?>