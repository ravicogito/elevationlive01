<div class='wrap'>
	
	<h1>Shopping Settings</h1>
	
	<?php global $Quote;
	
	$options = $Quote->get_shopping_option('shopping_options');
	$options = unserialize($options);
	
	?>
	
	<h2>Rates / Options</h2>
	<form method='post'>
	<input type='hidden' name='ems_cmd' value='update_shopping_packages' />
			<table class='widefat'>
		
		
		<thead>
			
			<tr>
				
				<th>Option Name</th>
				<th>Option Price (£)</th>
				<th>Option Max Packages</th>
				<th>Actions</th>
				
			</tr>
			
		</thead>
		
		<tbody>
			<?php $i = 0; foreach($options as $option):?>
			<tr>
				<td><input type='text' name='newshoppingoption[<?php echo $i;?>][name]' value='<?php echo $option['name'];?>' /></td>
				<td><input type='text' name='newshoppingoption[<?php echo $i;?>][price]' value='<?php echo $option['price'];?>' /></td>
				<td><input type='text' name='newshoppingoption[<?php echo $i;?>][packages]' value='<?php echo $option['packages'];?>' /></td>
				<td><a class='delete-shopping-option button-secondary' href='Javascript:void(0);'>Delete</a></td>
			</tr>
			<?php $i++; endforeach; ?>
			<tr>
				<td><input type='text' name='newshoppingoption[<?php echo $i;?>][name]'  /></td>
				<td><input type='text' name='newshoppingoption[<?php echo $i;?>][price]' placeholder='DO NOT ENTER £ SIGN'  /></td>
				<td><input type='text' name='newshoppingoption[<?php echo $i;?>][packages]'  /></td>
				<td><input type='submit' class='button-primary' value='Add Option / Update' /></td>
				
			</tr>
			
		</tbody>
		
	</table>
	</form>
	
	
	<h3>Price per additional package:</h3>
	<form method='post'>
	<input type='text' name='price_per_shopping_package' value='<?php echo $Quote->get_shopping_option('price_per_shopping_package');?>' /> <input type='submit' class='button-primary' value='Update' />
	<input type='hidden' name='ems_cmd' value='update_shopping_package_price' />
	</form>
</div>


<script type='text/javascript'>


$ = jQuery;
	
	$(document).ready(function(){
		
		$('.delete-shopping-option').click(function(){
			
			$(this).parent().parent().remove();
		});
	});
</script>