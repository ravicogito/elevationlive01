<?php include('wp-load.php');
set_time_limit(0);
$sql = "SELECT * FROM ems_pricing WHERE converted = 0 LIMIT 400";

$results = $wpdb->get_results($sql);

echo "<pre>";


function get_currency_rate($currency){
	
	global $wpdb;
	
	$sql = "SELECT * FROM ems_currencies WHERE currency = '$currency'";
	$row = $wpdb->get_row($sql);
	
	return $row->gbp_rate;
	
}

foreach($results as $result):
$id = $result->id;
//print_r($result);
$rate = get_currency_rate($result->currency);

$data['price_5'] = ($result->price_5 * $rate);
$data['price_15'] = ($result->price_15 * $rate);
$data['price_30'] = ($result->price_30 * $rate);

$data['price_per_kilo'] = number_format($data['price_30']/30, 2);
$data['converted'] = 1;

$where = array('id' => $id);

$wpdb->update('ems_pricing', $data, $where);

endforeach;