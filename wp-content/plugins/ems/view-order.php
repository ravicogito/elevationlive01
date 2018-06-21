<h1>Order Information</h1>



<style type='text/css'>

	#order-main{ width:80%; float:left; background-color:#fff; padding:30px; box-sizing:border-box; margin-top: 30px;}
	
	#order-sidebar{ width:17%; float:right; background-color:#eaeaea; padding:30px; box-sizing:border-box;margin-top: 30px;}
	
	#delivery-address{ width:49%; float:right; text-align: center; font-size: 18px;}
	
	#collection-address{ width:49%; float:left; text-align: center; font-size: 18px;}

</style>


<?php $order = $Quote->get_order($_GET['view']); $parcels = json_decode($order->parcels); 
$ems_session = $order->ems_session;
?>
<?php if($order->is_paid == 0):?><div style='font-size:50px; color:#f00;'>UNPAID</div><?php endif;?>
<?php if(isset($_GET['regenerate_label'])):$parcels = json_decode($order->parcels);?>
<?php $p = 1; foreach($parcels as $parcel):?>
<?php $rate = $parcel->rate_object;

global $wpdb;
// Purchase the desired rate.
$transaction = Shippo_Transaction::create( array( 
    'rate' => $rate, 
    'label_file_type' => "PDF", 
    'async' => false ) );

echo "<pre>";
print_r($transaction);
echo "</pre>";

// Retrieve label url and tracking number or error message
if ($transaction->status == "SUCCESS"){
    $data['label_url'] = $transaction->label_url;
	$data['invoice_url'] = $transaction->commercial_invoice_url;
	$prov = $transaction->tracking_url_provider;
	$x = explode('AWB=', $prov);
	$tracking_number = $x[1];
    $data['transaction_reference'] = $tracking_number;
	$data['description'] = 'Parcel '.$p;
	$data['ems_session'] = $ems_session;
	$data['rate_object'] = $rate;
	
	$wpdb->insert('ems_transactions', $data);
	//commercial_invoice_url
	
	
}else {
    echo "<span style='font-size:30px; color:#f00;'>".$transaction["messages"][0]->text."</span>";
}
?>
<?php $p++; endforeach; $order = $Quote->get_order($_GET['view']); endif;?>

	<?php $oi = "4dc1b7feb01a4c2c9e4f26c1e0fb10d8";

    $shipment = Shippo_Shipment::retrieve( $oi );
//print_r($shipment);

$carrier_account = "501c726e695042db80ab01cacfb64004";

$accounts = Shippo_CarrierAccount::all();
//echo "<pre>";
//print_r($accounts);

?>
	

<div id='order-main'>
	
	<a class='button-primary' href='admin.php?page=ems_orders&view=<?php echo $_GET['view'];?>&regenerate_label'>Repurchase Labels</a>
	<h2>User Information</h2>
	
	<?php if($order->ems_user_id == 0):?>
	<p><strong>Customer Name: </strong> <?php echo $order->nouser_firstname . ' ' . $order->nouser_lastname;?><br />
		<strong>Customer Email: </strong> <?php echo $order->nouser_email;?></strong></p>
		
	<?php else:?>
	
	<p><strong>Customer Name: </strong> <?php echo $order->user_info->first_name . ' ' . $order->user_info->last_name;?><br />
	<strong>Customer Email: </strong> <?php echo $order->user_info->email_address;?><br />
	<strong>User ID: </strong><?php echo $order->user_info->id;?></p>
	
	<?php endif;?>
	
	<hr />
	
	<h2>Parcels</h2>
	
	
	<table class='widefat'>
		
		<thead>
			
			<tr>
				
				<th>Description</th>
				<th>Dimensions</th>
				<th>Weight</th>
				<th>Price</th>
				<th>Original Price</th>
				<th>Shipping Label</th>
				<th>Commercial Invoice</th>
				<th>Tracking Code</th>
				
			</tr>
			
		</thead>
		
		<tfoot>
			
			<tr>
				
				<th>Description</th>
				<th>Dimensions</th>
				<th>Weight</th>
				<th>Price</th>
				<th>Original Price</th>
				<th>Shipping Label</th>
				<th>Commercial Invoice</th>
				<th>Tracking Code</th>
				
			</tr>
			
		</tfoot>
		
		<tbody>
			<?php foreach($parcels as $parcel):
			
			
				$found = 0;
			
				foreach($order->transactions as $transaction):
					if($transaction->rate_object == $parcel->rate_object):
						$found = 1;
			
						$shipping_label = $transaction->label_url;
						$tracking_code = $transaction->transaction_reference;
					endif;
				endforeach;
			
			?>
			<tr>
				<td><?php echo $parcel->description;?></td>
				<td><?php echo $parcel->dimensions;?></td>
				<td><?php echo $parcel->weight;?></td>
				<td>£<?php echo $parcel->price;?></td>
				<td>£<?php echo $parcel->original_price;?></td>
				<td><?php if($shipping_label != ''):?>
				<a href='<?php echo $shipping_label;?>'>Click to view</a><?php else:?>WAITING LABEL<?php endif;?></td>
				<td><?php if($transaction->invoice_url != ''):?><a href='<?php echo $transaction->commercial_invoice_url;?>' target='_blank'>Click to view</a><?php else:?>WAITING INVOICE<?php endif;?></td>
				<td><?php if($found == 1): echo $tracking_code; else: echo 'WAITING TRACKING CODE'; endif;?></td>
				
				
			</tr>
			<?php endforeach;?>
			
		</tbody>
		
	</table>
	<br>
<br>

	<hr />
	
	
	<div id='collection-address'>
	
	<h3>Collection Address</h3>
	
		<?php $collection_address = unserialize($order->collection_address); $address_type = $collection_address['addressType']; ?>
		<p>
			<strong>Address Type: </strong><br />
			
			<?php switch($address_type):
			
			case 1:
			 echo 'House';
			break;
			
			case 2:
			 echo 'Apartment/Flat';
			break;
			
			case 3:
			 echo 'Business';
			break;
			
			case 4:
			 echo 'University Accommodation';
			break;
			
			case 5:
			 echo 'Hotel';
			break;
			
			case 6:
			 echo 'Other';
			break;
			
			endswitch;?>
			
			
		</p>
		
		<p><strong><?php echo $collection_address['custName'];?></strong><br />
		<strong><?php echo $collection_address['custPhone_CountryCode'].$collection_address['custPhone'];?></strong></p>
		
		<p><?php echo $collection_address['custAddress1'];?><br /><?php echo $collection_address['custAddress2'];?><br /><?php echo $collection_address['cusPostcode'];?><br /><?php echo $collection_address['custCity'];?><br /><?php echo $collection_address['custPostcode'];?><br /><?php echo $collection_address['country'];?></p>
		
		<?php if(isset($collection_address['buzzer']) && $collection_address['buzzer'] != ''):?>
		<p><strong>Buzzer: </strong><?php echo $collection_address['buzzer'];?></p>
		<?php endif;?>
	</div>
	
	
	<div id='delivery-address'>
	
			<h3>Delivery Address</h3>
	
		<?php $delivery_address = unserialize($order->delivery_address); $address_type = $delivery_address['addressType']; ?>
		<p>
			<strong>Address Type: </strong><br />
			
			<?php switch($address_type):
			
			case 1:
			 echo 'House';
			break;
			
			case 2:
			 echo 'Apartment/Flat';
			break;
			
			case 3:
			 echo 'Business';
			break;
			
			case 4:
			 echo 'University Accommodation';
			break;
			
			case 5:
			 echo 'Hotel';
			break;
			
			case 6:
			 echo 'Other';
			break;
			
			endswitch;?>
			
			
		</p>
		
		<p><strong><?php echo $delivery_address['custName'];?></strong><br />
		<strong><?php echo $delivery_address['custPhone_CountryCode'].$delivery_address['custPhone'];?></strong></p>
		
		<p><?php echo $delivery_address['custAddress1'];?><br /><?php echo $delivery_address['custAddress2'];?><br /><?php echo $delivery_address['cusPostcode'];?><br /><?php echo $delivery_address['custCity'];?><br /><?php echo $delivery_address['custPostcode'];?><br /><?php echo $delivery_address['country'];?></p>
		
		<?php if(isset($delivery_address['buzzer']) && $delivery_address['buzzer'] != ''):?>
		<p><strong>Buzzer: </strong><?php echo $delivery_address['buzzer'];?></p>
		<?php endif;?>

	</div>
	
<div style='clear:both;'></div>

	
			
</div><!-- END ORDER MAIN -->




<div id='order-sidebar'>
	
	<h3>Order Total: £<?php echo $order->total;?></h3>
	    
    <p><strong>Shipping Date:</strong><br /><?php $x = explode('-',$order->shipping_date);
			$y = $x[0]; $m = $x[1]; $d = $x[2];
			$month = $m+1;
			$date = $y.'-'.$month.'-'.$d;
		
			$sd = strtotime($date);
			$shipping_date = date('d/m/Y', $sd); echo $shipping_date;?></p>
	
	<p><strong>Send Free Labels: </strong><br /><?php if($order->send_free_labels == 1):?>Yes<?php else:?>No<?php endif;?></p>
	
	<p><strong>Post Free Labels: </strong><br /><?php if($order->post_free_labels == 1):?>Yes<?php else:?>No<?php endif;?></p>
	
	<p><strong>Cancellation Cover: </strong><br /><?php if($order->cancellation_cover == 1):?>Yes<?php else:?>No<?php endif;?></p>
	
	<p><strong>Level of Cover: </strong><br />
	
	<?php $loc = $order->level_of_cover;
		
		
		switch($loc){
				
			case 'Please Select':
				echo '£125 Cover (Free)';
			break;
				
				case 1:
				echo '£125 Cover (Free)';
			break;
				
					case 2:
				echo '£250 Cover (£4.00)';
			break;
				
						case 3:
				echo '£500 Cover (£8.00)';
			break;
				
			case 4:
				echo '£1000 Cover (£16.00)';
				break;
				
			case 5:
				echo '£1500 Cover (£24.00)';
				break;
				
			default:
				echo '£125 Cover (Free)';
				break;
				
				
		}
		
		?>
	
	</p>
	
	<p><strong>Why Us:</strong><br /><?php echo $order->why_us;?></p>
	
	
</div><!-- END ORDER SIDEBAR -->


<div style='clear:both;'></div>