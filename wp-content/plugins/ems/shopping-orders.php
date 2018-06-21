<div class='wrap'>
	
	<?php if(isset($_GET['view_order'])): include('view-shopping-order.php'); else:?>
	<h1>SHOPPING Orders</h1>
	
	<table class='widefat'>
		
		
		<thead>
			
			<tr>
				<th>Customer</th>
				<th>Package</th>
				<th>Extra Packages</th>
				<th>Total</th>
				<th>Actions</th>
				
			</tr>
			
		</thead>
		<?php global $Quote; $orders = $Quote->get_shopping_orders(); $ppp = $Quote->get_shopping_option('price_per_shopping_package'); ?>
		<tbody>
			<?php foreach($orders as $order):$total = (($ppp*$order->extra_packages)+$order->amount);?>
			<tr>
				<td><?php echo $order->user->first_name . ' ' . $order->user->last_name;?></td>
				<td><?php echo $order->product_title;?></td>
				<td><?php echo $order->extra_packages;?></td>
				<td>£<?php echo number_format($total, 2);?></td>
				<td><a href='admin.php?page=ems_shopping_orders&view_order=<?php echo $order->id;?>' class='button-primary'>View</a></td>
				
			</tr>
			<?php endforeach;?>
			
			
		</tbody>
		
	</table>
	<?php endif;?>
	
</div>