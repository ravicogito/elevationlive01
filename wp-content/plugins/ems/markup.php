
<style type='text/css'>


	.base-markup{ padding:20px; box-sizing:border-box; background-color:#fff; margin-bottom: 30px;}
</style>

<div class='wrap'>
	<?php  global $Quote; ?>
	<h1>Pricing</h1>
	
	<div class='base-markup'>
		<form method='post'>
		<div class='form-control'>
			<input type='hidden' name='ems_cmd' value='update_base_rate' />
			<label>Base Markup (%):</label>
			
			<input type='text' class='ibox' name='base_markup' value='<?php echo $Quote->get_ems_option('base_markup');?>' /> <input type='submit' class='button-primary' value='Update Base Rate' />
			
		</div>
		</form>
		
	</div>
	
	<?php
	
	$places = $Quote->get_places(); ?>
	<div class='markup-panel'>
		
		<h2>Add New Markup Between Locations:</h2>
		
		<form method='post'>
		
		<input type='hidden' name='ems_cmd' value='add_markup' />
		
		<table class='widefat'>
			
			<thead>
				
				<tr>
					<th>Collection Country</th>
					<th>Delivery Country</th>
					<th>Default Markup (%)</th>
					<th>5kg Markup (%)</th>
					<th>15kg Markup (%)</th>
					<th>30kg Markup (%)</th>
					<th>Service Level</th>
					
				</tr>
				
			</thead>
			
			<tbody>
				
				
				<tr>
					
					<th><select class='sbox' name='newmarkup[collection_country]'>
						
						<?php foreach($places as $place):?>
						<option value='<?php echo $place->country_code;?>'><?php echo $place->name;?></option>
						<?php endforeach;?>
						
					</select></th>
					<th><select class='sbox' name='newmarkup[delivery_country]'><?php foreach($places as $place):?>
						<option value='<?php echo $place->country_code;?>'><?php echo $place->name;?></option>
						<?php endforeach;?></select></th>
					<th><input type='text' class='ibox' name='newmarkup[markup]' /></th>
					<th><input type='text' class='ibox' name='newmarkup[markup_5]' /></th>
					<th><input type='text' class='ibox' name='newmarkup[markup_15]' /></th>
					<th><input type='text' class='ibox' name='newmarkup[markup_30]' /></th>
					<th><select name='newmarkup[service_level]'><option value='standard'>Standard</option><option value='express'>Express</option></select></th>
					
				</tr>
				
			</tbody>
			
		</table><br>

		
		<input type='submit' class='button-primary' value='Add Markup' />
		
		</form>
		
	</div><!-- END MARKUP PANEL -->
	
	
	<div class='current-markups'>
		
		
		<h2>Current Pricing Markups:</h2>
		
		<?php if(isset($_GET['u'])):?>
		<div style='padding:20px; background-color:#fff; margin:10px 0; font-size:20px; color:#1CAA3D;'>Price matrix updated!</div>
		<?php endif;?>
		
		<table class='widefat'>
			
			<thead>
				<tr>
					<th>Collection Country</th>
					<th>Delivery Country</th>
					<th>Markup (%)</th>
					<th>5kg Markup (%)</th>
					<th>15kg Markup (%)</th>
					<th>30kg Markup (%)</th>
					<th>Service Level</th>
					<th>Actions</th>
					
				</tr>
				
			</thead>
			
			<tfoot>
				<tr>
					<th>Collection Country</th>
					<th>Delivery Country</th>
					<th>Markup (%)</th>
					<th>5kg Markup (%)</th>
					<th>15kg Markup (%)</th>
					<th>30kg Markup (%)</th>
					<th>Service Level</th>
					<th>Actions</th>
					
				</tr>
				
			</tfoot>
			
			<tbody>
				<?php $markups = $Quote->get_markups();
				
				foreach($markups as $markup):?>
				<tr>
					<td><?php echo $markup->collection_country;?></td>
					<td><?php echo $markup->delivery_country;?></td>
					<td><input type='text' class='default-markup' value='<?php echo $markup->markup;?>' /></td>
					
					<td><input type='text' class='markup-5' value='<?php echo $markup->markup_5;?>' /></td>
					<td><input type='text' class='markup-15' value='<?php echo $markup->markup_15;?>' /></td>
					<td><input type='text' class='markup-30' value='<?php echo $markup->markup_30;?>' /></td>
					<td><select class='service-level'><option value='standard' <?php if($markup->service_level == 'standard'):?>selected='selected'<?php endif;?>>Standard</option><option value='express' <?php if($markup->service_level == 'express'):?>selected='selected'<?php endif;?>>Express</option></select></td>
					<td><a class='button-primary save-markups' href='Javascript:void(0);' data-id='<?php echo $markup->id;?>'>Update</a> <a class='button-secondary delete-markup' href='admin.php?page=ems_markup&ems_cmd=delete_markup&markup_id=<?php echo $markup->id;?>'>Delete</a></td>
					
				</tr>
				<?php endforeach;?>
			</tbody>
			
		</table>
		
	</div>
	
	<script type='text/javascript'>
	
	$ = jQuery;
		
		$(document).ready(function(){
			
			$('.delete-markup').click(function(){
			
			var c = confirm('Are you sure you want to permanently delete this markup price?');
			
			if(c === true){ return true; } else{ return false; }
				
			});
			
			$('.save-markups').click(function(){
				
				var id = $(this).attr('data-id');
				var default_markup = $('.default-markup', $(this).parent().parent()).val();
				var markup_5 = $('.markup-5', $(this).parent().parent()).val();
				var markup_15 = $('.markup-15', $(this).parent().parent()).val();
				var markup_30 = $('.markup-30', $(this).parent().parent()).val();
				var service_level = $('.service-level', $(this).parent().parent()).val();
				
				
				var str = 'admin.php?page=ems_markup&ems_cmd=update_country_markups&m5='+markup_5+'&m15='+markup_15+'&m30='+markup_30+'&dm='+default_markup;
				str += '&markup_id='+id+'&sl='+service_level;
				
				window.location = str;
			});
			
		});
	</script>
	
	
	
</div>