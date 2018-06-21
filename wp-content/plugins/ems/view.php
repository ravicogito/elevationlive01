<h2 style='width:50%; float:left;'>Order Information</h2>

<a class='button-primary' href='admin.php?page=billing/admin.php' style='float:right;'>&laquo; Back to Orders</a>
<div style='clear:both;'></div>

<?php $id = $_GET['view'];

global $wpdb;

$sql = "SELECT * FROM ".$wpdb->prefix."billing WHERE id = $id";

$result = $wpdb->get_row($sql);

?>

<div style='width:48%; float:left; padding:20px; background-color:#eaeaea; box-sizing:border-box;'>
<h2 style='margin-top:0;'>Order Details</h2>
<table class='widefat'>
	
	<tr>
		
		<td><strong>Order Ref:</strong></td>
		<td><?php echo $result->order_id;?></td>
		
	</tr>
	<tr>
		
		<td><strong>Customer Name:</strong></td>
		<td><?php echo $result->first_name . ' ' . $result->last_name;?></td>
		
	</tr>
	<tr>
		
		<td><strong>Customer Email:</strong></td>
		<td><?php echo $result->email;?></td>
		
	</tr>
	<tr>
		
		<td><strong>Copies Ordered:</strong></td>
		<td><?php echo $result->quantity;?></td>
		
	</tr>
	<tr>
		
		<td><strong>Price Paid:</strong></td>
		<td><?php echo number_format($result->price, 2);?> GBP</td>
		
	</tr>
	<tr>
		
		<td valign="top"><strong>Billing Address:</strong></td>
		<td><?php echo $result->billing_address;?></td>
		
	</tr>
	
	<tr>
		
		<td><strong>Order Date:</strong></td>
		<td><?php $date = strtotime($result->date);echo date('D d M y', $date);?></td>
		
	</tr>
	
	
</table>


</div>


<div style='width:48%; float:right; padding:20px; background-color:#eaeaea; box-sizing:border-box'>
<h2 style='margin-top:0;'>Delivery Details</h2>
<table class='widefat'>
	
	<tr>
		
		<td><strong>Delivery Address:</strong></td>
		<td><?php echo $result->delivery_address;?></td>
		
	</tr>
	
	</table>

</div>

<div style='clear:both;'></div>