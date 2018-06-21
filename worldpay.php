<?php
set_time_limit(0);
include('wp-load.php');

$response = $_REQUEST;


$status = $response['transStatus'];

if($status == 'Y'):

	global $wpdb;

	$transaction_id = $response['transId'];

	$amount = $response['cost'];

	$ems_session = $response['cartId'];

	if(substr($ems_session, 0, 4) == 'SHOP'):

$data = array('paid' => 1);

	$where = array('ems_session' => $ems_session);
	$wpdb->update('ems_shopping_purchases', $data, $where);

	$Quote->send_shopping_email($ems_session);


	else:

	

	$data = array('is_paid' => 1);
	$where = array('ems_session' => $ems_session);

	$wpdb->update('ems_shipments', $data, $where);

	global $Quote;

	$Quote->purchase_orders($ems_session);

	endif;

endif;


?>