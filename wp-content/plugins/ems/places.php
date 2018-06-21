<div class='wrap'>
	
	
	<h1>Places</h1>
	
	<p>You can edit the allowed places to search for quotes on below. Note that each country needs a default city and post(zip) code.</p>
	<?php global $Quote;?>
	
		
	<?php $places = $Quote->get_places();?>
	
	<form method='post'>
	<table class='widefat'>
		
		
		<thead>
			
			<tr>
				<th>Name</th>
				<th>City</th>
				<th>Post/zip code</th>
				<th>Country Code</th>
				<th>Is Popular?</th>
				<th>Order</th>
				<th>Remove</th>
				
			</tr>
			
		</thead>
		
			<tfoot>
			
			<tr>
				<th>Name</th>
				<th>City</th>
				<th>Post/zip code</th>
				<th>Country Code</th>
				<th>Is Popular?</th>
				<th>Order</th>
				<th>Remove</th>
				
			</tr>
			
		</tfoot>
		
		<tbody id='to-append-to'>
			
			<?php $i = 0;foreach($places as $place):?>
			<tr id='row-<?php echo $i;?>'>
				
				<td><input type='text' class='ibox' name='ems_places[<?php echo $i;?>][name]' value='<?php echo $place->name;?>' /></td>
				<td><input type='text' class='ibox' name='ems_places[<?php echo $i;?>][city]' value='<?php echo $place->city;?>' /></td>
				<td><input type='text' class='ibox' name='ems_places[<?php echo $i;?>][postcode]' value='<?php echo $place->postcode;?>' /></td>
				<td><input type='text' class='ibox' name='ems_places[<?php echo $i;?>][country_code]' value='<?php echo $place->country_code;?>' /></td>
				<td><select name='ems_places[<?php echo $i;?>][is_popular]'>
					<option value='1' <?php if($place->is_popular == 1):?>selected='selected'<?php endif;?>>Yes</option>
					<option value='0' <?php if($place->is_popular == 0):?>selected='selected'<?php endif;?>>No</option>
					
				</select></td>
				<td><input type='text' class='ibox' name='ems_places[<?php echo $i;?>][orderx]' value='<?php echo $place->orderx;?>' /></td>
				<td><a href='Javascript:void(0);' onClick='remove_row(<?php echo $i;?>);'>Remove</a></td>
				
			</tr>
			<?php $i++; endforeach;?>
			
		</tbody>
		
	</table><br>

	
		<a class='button-secondary' href='Javascript:void(0);' id='add-new-place'>+ Add New Destination</a> <input type='submit' class='button-primary' value='Update List' />
		
		<input type='hidden' name='ems_cmd' value='update_places' />
	</form>
</div>

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type='text/javascript'>

	$ = jQuery;
	
	function remove_row(i){
		
		$('#row-'+i).remove();
		
	}
	
	$(document).ready(function(){
		
		var current_index = <?php echo $i;?>;
		
		$('#add-new-place').click(function(){
			
			var str = "<tr id='row-"+current_index+"'>";
			str += "<td><input type='text' class='ibox' name='ems_places["+current_index+"][name]' /></td>";
			str += "<td><input type='text' class='ibox' name='ems_places["+current_index+"][city]' /></td>";
			str += "<td><input type='text' class='ibox' name='ems_places["+current_index+"][postcode]' /></td>";
			str += "<td><input type='text' class='ibox' name='ems_places["+current_index+"][country_code]' /></td>";
			str += "<td><select name='ems_places["+current_index+"][is_popular]'>";
			str += "<option value='1'>Yes</option><option value=''>No</option></select></td>";
			str += "<td><input type='text' class='ibox' name='ems_places["+current_index+"][orderx]' /></td>";
			str += "<td><a href='Javascript:void(0);' onClick='remove_row("+current_index+");'>Remove</a></td>";
			str += "</tr>";
				
			current_index++;
			
			$('#to-append-to').append(str);
			
		});
		
		

		
		
	});

</script>