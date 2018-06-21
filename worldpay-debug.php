<?php
set_time_limit(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('wp-load.php');


	$ems_session = '8326763336218296418586497';


	global $wpdb;

	$data = array('is_paid' => 1);
	$where = array('ems_session' => $ems_session);

	$wpdb->update('ems_shipments', $data, $where);

	global $Quote;

	$Quote->purchase_orders_debug($ems_session);




?>