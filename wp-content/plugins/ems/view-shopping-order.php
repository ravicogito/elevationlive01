
<style type='text/css'>

	#buy-shipping-labels label{ display: block; font-size: 18px; font-weight: bold; margin-bottom: 2px;}
	
	#buy-shipping-labels .ibox{ display: block; width:100%; height: 30px; line-height: 30px; border:solid 2px #000; margin-bottom: 10px;}
</style>

<h1>Order Information</h1>


<?php global $Quote; 

$order_id = $_GET['view_order'];

$order = $Quote->get_single_shopping_order($order_id);


$ppp =  $Quote->get_shopping_option('price_per_shopping_package');
?>


<div style='width:50%; float:left;box-sizing:border-box;'>

<h2>Package: <?php echo $order->product_title;?></h2>
	
	<table class='widefat'>
		
		<tr>
			
			<td>Customer Name:</td>
			<td><?php echo $order->user->first_name . ' ' . $order->user->last_name;?></td>
			
		</tr>
		
		<tr>
			
			<td>Customer Email:</td>
			<td><?php echo $order->user->email_address;?></td>
			
		</tr>
		
          
		<tr>
			<td>Collection Address:</td>
			<td><?php echo $order->collection_address['custName'];?><br /><input type='hidden' id='collection_name' value='<?php echo $order->collection_address['custName'];?>' />
			<?php echo $order->collection_address['custAddress1'];?><br /><input type='hidden' id='collection_address1' value='<?php echo $order->collection_address['custAddress1'];?>' />
			<?php echo $order->collection_address['custAddress2'];?><br /><input type='hidden' id='collection_address2' value='<?php echo $order->collection_address['custAddress2'];?>' />
			<?php echo $order->collection_address['custCity'];?><br /><input type='hidden' id='collection_city' value='<?php echo $order->collection_address['custCity'];?>' />
			<?php echo $order->collection_address['cusPostcode'];?><br /><input type='hidden' id='collection_postcode' value='<?php echo $order->collection_address['cusPostcode'];?>' />
			<?php echo $order->collection_address['country'];?><br /><input type='hidden' id='collection_country' value='<?php echo $order->collection_address['country'];?>' />
			<?php echo $order->collection_address['custPhone_CountryCode'];?><?php echo $order->collection_address['custPhone'];?><br /><input type='hidden' id='collection_phone' value='<?php echo $order->collection_address['custPhone_CountryCode'];?><?php echo $order->collection_address['custPhone'];?>' /><input type='hidden' id='collection_state' value='<?php echo $order->collection_address['state'];?>' /><input type='hidden' id='delivery_state' value='<?php echo $order->delivery_address['state'];?>' />
			</td>
			
		</tr>
		
		<tr>
			<td>Delivery Address:</td>
			<td><?php echo $order->delivery_address['custName'];?><br /><input type='hidden' id='delivery_name' value='<?php echo $order->delivery_address['custName'];?>' />
			<?php echo $order->delivery_address['custAddress1'];?><br /><input type='hidden' id='delivery_address1' value='<?php echo $order->delivery_address['custAddress1'];?>' />
			<?php echo $order->delivery_address['custAddress2'];?><br /><input type='hidden' id='delivery_address2' value='<?php echo $order->delivery_address['custAddress2'];?>' />
			<?php echo $order->delivery_address['custCity'];?><br /><input type='hidden' id='delivery_city' value='<?php echo $order->delivery_address['custCity'];?>' />
			<?php echo $order->delivery_address['cusPostcode'];?><br /><input type='hidden' id='delivery_postcode' value='<?php echo $order->delivery_address['cusPostcode'];?>' />
			<?php echo $order->delivery_address['country'];?><br /><input type='hidden' id='delivery_country' value='<?php echo $order->delivery_address['country'];?>' />
			<?php echo $order->delivery_address['custPhone_CountryCode'];?><?php echo $order->delivery_address['custPhone'];?><br /><input type='hidden' id='delivery_phone' value='<?php echo $order->delivery_address['custPhone_CountryCode'];?><?php echo $order->delivery_address['custPhone'];?>' />
			</td>
			
		</tr>
		<tr>
			<td>Order Amount:</td>
			<?php $total = (($ppp*$order->extra_packages)+$order->amount); ?>
			<td>£<?php echo number_format($total, 2);?></td>
			
		</tr>
		
		<tr>
			<td>Extra packages:</td>
			<td><?php echo $order->extra_packages;?></td>
			
		</tr>
		
	</table>
	
	
	
</div>


<div style='width:48%; float:right; background-color:#EFAFB0; padding:20px; border:solid 1px #000; box-sizing:border-box; margin-top: 54px;'>
	
	
	<h2 style='margin-top:0px;'>Assign Packages From Worldpay Directly:</h2>
	
	<?php $parcels = $Quote->get_parcels_for_order($order->id);
	
	if(!empty($parcels)):?>
	<h3>ASSIGNED PARCELS</h3>
	
	<table class='widefat'>
		
		<thead>
			<tr>
				<th>Description</th>
				<th>Dimensions</th>
				<th>Price</th>
				
				
			</tr>
			
		</thead>
		
		<tbody>
			
			<?php foreach($parcels as $parcel):?>
			<tr>
				<td><?php echo $parcel->description;?></td>
				<td><?php echo $parcel->dimensions;?></td>
				<td><?php echo number_format($parcel->price, 2);?></td>
				
			</tr>
			<?php endforeach;?>
			
		</tbody>
		
	</table>
	<?php  endif;?>
	
	<p><strong>IMPORTANT: </strong>THIS WILL PURCHASE REAL SHIPPING LABELS IMMEDIATELY</p>
	
	<hr />
	
	<div id='buy-shipping-labels'>
		
		<label>Item description:</label>
		<input type='text' class='ibox' id='newpackage-description' />
		
		<label>Width (cm):</label>
		<input type='text' class='ibox' id='newpackage-width' />
		
		<label>Height (cm):</label>
		<input type='text' class='ibox' id='newpackage-height' />
		
		<label>Length (cm):</label>
		<input type='text' class='ibox' id='newpackage-length' />
		
		<label>Weight (kg):</label>
		<input type='text' class='ibox' id='newpackage-weight' />
		
		<label>Shipment Date</label>
		<input type='text' class='ibox' id='newpackage-shipping-date' placeholder='YYYY-MM-DD' value='<?php echo date('Y-m-d');?>' />
		
		<label>Declare Customs?</label>
		
		<select class='ibox' id='newpackage-declare'>
			<option value='yes'>Yes</option>
			<option value='no'>No</option>
			
		</select>
		
		<label>Customs value: (GBP)</label>
		<input type='text' class='ibox' id='newpackage-customs' placeholder='Do not enter £' />
		
		<label>Customs type:</label>
		
	
	<select class='ibox' id='newpackage-customs-type'>
		
		<option value='DOCUMENTS'>Documents</option>
		<option value='GIFT'>GIFT</option>
		<option value='SAMPLE'>Sample</option>
		<option value='MERCHANDISE'>Merchandise</option>
		<option value='HUMANITARIAN_'>Humanitarian</option>
		<option value='DONATION'>Donation</option>
		<option value='RETURN_'>Return</option>
		<option value='OTHER'>Other</option>
		
	</select>
	
	<label>Customs type other:</label>
	
	<input type='text' class='ibox' id='newpacakge-customs-type-other' />
	
	<a class='button-primary' href='Javascript:void(0);' id='add-new-admin-package'>+ Look for rates</a>
		
	</div>
	
	<div id='choose'></div>
	
</div>




<div style='clear:both;'></div>




<script type='text/javascript'>

$ = jQuery;
	
	function add_to_order(elem){
		
		var rate_id = $(elem).attr('data-rate');
		var price = $(elem).attr('data-price');
		var shipment_object = $(elem).attr('data-object-id');
		var description= $(elem).attr('data-description');
		var dimensions = $(elem).attr('data-dimensions');
		var provider = $(elem).attr('data-provider');
		var order_id = <?php echo $order->id;?>;
		
		$.post('admin-ajax.php', {'action': 'add_bag_to_shopping',
								 'rate_id': rate_id,
								 'price': price,
								 'shipment_object': shipment_object,
								 'description': description,
								 'dimensions': dimensions,
								 'provider': provider,
								 'order_id': order_id}).done(function(data){
			    location.reload();
			
		});
		
		
		
	}
	
	$(document).ready(function(){
		
		
		$('#add-new-admin-package').click(function(){
			
			var collection_name = $('#collection_name').val();
            var collection_address1 = $('#collection_address1').val();
            var collection_address2 = $('#collection_address2').val();
            var collection_city = $('#collection_city').val();
            var collection_postcode = $('#collection_postcode').val();
            var collection_country = $('#collection_country').val();
            var collection_phone = $('#collection_phone').val();
            var collection_state = $('#collection_state').val();
            var delivery_name = $('#delivery_name').val();
            var delivery_address1 = $('#delivery_address1').val();
            var delivery_address2 = $('#delivery_address2').val();
            var delivery_city = $('#delivery_city').val();
            var delivery_postcode = $('#delivery_postcode').val();
            var delivery_country = $('#delivery_country').val();
            var delivery_phone = $('#delivery_phone').val();
            var delivery_state = $('#delivery_state').val();
			var item_description = $('#newpackage-description').val();
			var width = $('#newpackage-width').val();
			var height = $('#newpackage-height').val();
			var length = $('#newpackage-length').val();
			var weight = $('#newpackage-weight').val();
			var shipping_date = $('#newpackage-shipping-date').val();
			var has_customs = $('#newpackage-declare').val();
			var customs_value = $('#newpackage-customs').val();
			var customs_type = $('#newpackage-customs-type').val();
			var customs_other = $('#newpackage-customs-type-other').val();
			
			$.post('admin-ajax.php', {
				'action': 'ems_admin_quote',
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
				
			}).done(function(data){
				
					
					var modal_str = '<table class="widefat">';	
					
				var obj = jQuery.parseJSON( data );
						
					if (typeof obj.message !== 'undefined') {
						$('.waiting-cover').fadeOut('fast');
						$('#message-modal .modal-body').html(obj.message);
						$('#message-modal').modal();
						return false;
					}	
					$.each(obj, function( index, therate ) {
	
					
					
					var object_id = therate.object_id;
					var amount = therate.amount;
					var provider = therate.provider;
					var rate = therate.rate;
					var op = therate.original_price;
					var thecurrency = therate.currency;
					var provider_image = therate.provider_image;
					var arrives_by = therate.arrives_by;
					var servicelevel_name = therate.servicelevel_name;
					var days = therate.days;
						
					
						
				
						
						var lwunits = 'cm';
						var wunits = 'kg';
					
						
						modal_str += "<tr><td><img src='"+provider_image+"' /><br />"+provider+"</td>";
						modal_str += "<td><strong>Arrives by:</strong> "+arrives_by+" ("+days+" days)<br />"+servicelevel_name+"</td>";
						modal_str += "<td>"+amount+" GBP</td>";
						modal_str += "<td><a class='button-secondary' href='Javascript:void(0);' data-price='"+amount+"' data-rate='"+rate+"' data-object-id='"+object_id+"'";
						modal_str += "data-description='"+item_description+"' data-dimensions='"+width+lwunits+" x "+height + lwunits+ " x " + length + lwunits+"'";
						modal_str += "data-weight='"+weight+wunits+"' data-orig='"+op+"' data-provider='"+provider+"'";
						modal_str += " onClick='add_to_order(this);'>+ Add to Shopping Order</a></td></tr>";
					
					
					
					
						
					});
						
						modal_str += "</table>";
						
						$('#choose').html(modal_str);
						
			});
			
		});
		
	});

</script>