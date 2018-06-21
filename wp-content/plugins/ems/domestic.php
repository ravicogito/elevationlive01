<div class='wrap'>
	
	<h1 class='page-title'>GB-GB Domestic Rates</h1>

<?php if(isset($_GET['sv'])):?>
<div style='background-color:#fff; padding:20px; box-sizing:border-box; margin:20px 0; font-size:20px; color:#00AC43;'>GB rates saved successfully!</div>
<?php endif;?>

<form method='post'>

<p><strong>Notes: </strong>empty rows will not be saved. Once saved, all rows will be ordered correctly.</p>

	<table class='widefat'>
		
		<thead>
			<tr>
				<th>KG</th>
				<th>LB</th>
				<th>Price</th>
				<th>Actions</th>
				
			</tr>
			
		</thead>
		
		<tfoot>
			<tr>
				<th>KG</th>
				<th>LB</th>
				<th>Price</th>
				<th>Actions</th>
				
			</tr>
			
		</tfoot>
		<?php global $Quote; $rates = $Quote->get_all_gb_rates(); ?>
		<tbody id='rates-table'>
			<?php $i = 0; foreach($rates as $rate):?>
			<tr>
				
				<td><input type='text' name='rates[<?php echo $i;?>][kg]' value='<?php echo $rate->kg;?>' /></td>
				<td><input type='text' name='rates[<?php echo $i;?>][lb]' value='<?php echo $rate->lb;?>' /></td>
				<td><input type='text' name='rates[<?php echo $i;?>][price]' value='<?php echo $rate->price;?>' /></td>
				<td><a class='button-secondary' href='Javascript:void(0);' onClick="jQuery(this).parent().parent().remove();">Delete rate</a></td>
				
			</tr>
			<?php $i++; endforeach;?>
		</tbody>
		
		
	</table><br>
<br>

<a class='button-secondary add-new-rate-row' href='Javascript:void(0);'>+ Add New Rate</a>
	<input type='hidden' name='ems_cmd' value='save_gb_rates' />
	<input type='submit' class='button-primary' style='float:right;' value='SAVE RATES' />
	<div style='clear:both;'></div>
		
	</form>
	
	<script type='text/javascript'>
	var last_loaded = <?php echo $i;?>;
	$ = jQuery;
		
		$('.add-new-rate-row').click(function(){
			
			var html = "<tr>";
				
	html += "<td><input type='text' name='rates["+last_loaded+"][kg]'  /></td>";
	html += "<td><input type='text' name='rates["+last_loaded+"][lb]' /></td>";
	html += "<td><input type='text' name='rates["+last_loaded+"][price]'  /></td>";
	html += "<td><a class='button-secondary' href='Javascript:void(0);'  onClick='jQuery(this).parent().parent().remove();'>Delete rate</a></td>";
	html += "</tr>";
			
			$('#rates-table').append(html);
			last_loaded++;
			
		});
	
	</script>
	
	
	
	
	
</div>