<?php include('wp-load.php');
set_time_limit(0);
global $wpdb;
global $Quote;
$sql = "SELECT * FROM ems_pricing WHERE done = 0 ORDER BY id DESC LIMIT 10";

$results = $wpdb->get_results($sql);

foreach($results as $result):

	$code = $result->from_country;

	$from_country_info = $Quote->get_country_from_code($code);
	
	$code = $result->to_country;

	$to_country_info = $Quote->get_country_from_code($code);

	$weights = array(5,15,30);

	$result_id = $result->id;

	foreach($weights as $weight):
				$from_address = array(
			'object_purpose' => 'QUOTE',
			'name' => 'Mr Hippo',
			'company' => 'Shippo',
			'street1' => '',
			'city' => $from_country_info->city,
			'state' => '',
			'zip' => $from_country_info->postcode,
			'country' => $result->from_country,
			'phone' => '+1 555 341 9393',
			'email' => 'mr-hippo@goshipppo.com',
		);

		// Example to_address array
		// The complete refence for the address object is available here: https://goshippo.com/docs/reference#addresses
		$to_address = array(
			'object_purpose' => 'QUOTE',
			'name' => 'Ms Hippo',
			'company' => 'San Diego Zoo',
			'street1' => '',
			'city' => $to_country_info->city,
			'state' => '',
			'zip' => $to_country_info->postcode,
			'country' => $result->to_country,
			'phone' => '+1 555 341 9393',
			'email' => 'ms-hippo@goshipppo.com',
		);

		// Parcel information array
		// The complete reference for parcel object is here: https://goshippo.com/docs/reference#parcels
		$parcel = array(
			'length'=> '5',
			'width'=> '2',
			'height'=> '5',
			'distance_unit'=> 'cm',
			'weight'=> $weight,
			'mass_unit'=> 'kg',
		);

		// Example shipment object
		// For complete reference to the shipment object: https://goshippo.com/docs/reference#shipments
		// This object has async=false, indicating that the function will wait until all rates are generated before it returns.
		// By default, Shippo handles responses asynchronously. However this will be depreciated soon. Learn more: https://goshippo.com/docs/async
		$shipment = Shippo_Shipment::create(
		array(
			'object_purpose'=> 'QUOTE',
			'address_from'=> $from_address,
			'address_to'=> $to_address,
			'parcel'=> $parcel,
			'async'=> false,
		));

		

		// Rates are stored in the `rates_list` array inside the shipment object
		$rates = $shipment['rates_list'];

		foreach($rates as $rate):

			if(in_array('CHEAPEST', $rate->attributes)):
	
				$amount = $rate->amount;
				$currency = $rate->currency;

				if($amount > 0):
					
					$data = array(
						'price_'.$weight => $amount,
						'currency' => $currency
					);
				
					$where = array(
						'id' => $result_id
					);

					$wpdb->update('ems_pricing', $data, $where);
				

				endif;
	
			endif;

		endforeach;

		

	endforeach;
$data = array('done'=> 1);
		$where = array('id' => $result_id);
		$wpdb->update('ems_pricing', $data, $where);
endforeach;

header('Location: quotesa.php');

?>