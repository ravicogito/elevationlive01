<div class='wrap'>
	
	<?php global $Quote;
	
	if(!isset($_GET['view'])):
	
	$orders = $Quote->get_orders();
	
	?>
	
	
	
	<h1>EMS Orders</h1>
	
	
	<table class='widefat'>
		
		
		<thead>
			
			<tr>
				<th>Customer Name</th>
				<th>Total</th>
				<th>Parcels</th>
				<th>Shipping Date</th>
				<th>Transaction Date</th>
				<th>Status</th>
				<th>Actions</th>
				
			</tr>
			
		</thead>
		
		
		<tfoot>
			
			<tr>
				<th>Customer Name</th>
				<th>Total</th>
				<th>Parcels</th>
				<th>Shipping Date</th>
				<th>Transaction Date</th>
				<th>Status</th>
				<th>Actions</th>
				
			</tr>
			
		</tfoot>
		
		<tbody>
		<?php foreach($orders as $order):
			
			
		
		
			$parcels = json_decode($order->parcels);
			$grand_total = 0;
			foreach($parcels as $parcel): 
				$grand_total += floatval(str_replace(',', '', $parcel->price));
			
			endforeach;
			
			$parcels_count = count($parcels);
			
			$x = explode('-',$order->shipping_date);
			$y = $x[0]; $m = $x[1]; $d = $x[2];
			$month = $m+1;
			$date = $y.'-'.$month.'-'.$d;
		
			$sd = strtotime($date);
			$shipping_date = date('d/m/Y', $sd);
			
			$td = strtotime($order->date);
			
			$transaction_date = date('d/m/Y H:i:s', $td);
		
			if($order->ems_user_id == 0):
				$customer_name = $order->nouser_first_name . ' ' . $order->nouser_last_name;
			else:
				$customer_name = $order->user_info->first_name . ' ' . $order->user_info->last_name;
			endif;
		
			if(!empty($order->transactions)): $status = '<span style="padding:4px; background-color:green; color:#fff;">SUCCESS</span>'; else: $status = '<span style="padding:4px; background-color:red; color:#fff;">QUEUED</span>'; endif;
			
		?>
		
		<tr>
			
			<td><?php echo $customer_name;?></td>
			<td><?php echo number_format($grand_total, 2);?> <?php if($order->currency == ''):?>GBP<?php else: echo $order->currency; endif;?></td>
			<td><?php echo $parcels_count;?></td>
			<td><?php echo $shipping_date;?></td>
			<td><?php echo $transaction_date;?></td>
			<td><?php echo $status;?></td>
			<td><a class='button-primary' href='admin.php?page=ems_orders&view=<?php echo $order->id;?>'>View Order</a> <a href='#' class='button-secondary'>Delete Order</a></td>
			
		</tr>
		
		
		<?php endforeach;?>
		</tbody>
	</table>
	
	<?php else:
	
	include('view-order.php');
	
	endif;?>
	
</div>