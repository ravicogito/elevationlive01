
<style type='text/css'>

	.row-left{ width:49%; float:left;}
	
	.row-right{ width:49%; float:right;}
</style>

<div class='wrap'>
	<?php if(!isset($_GET['month']) && !isset($_GET['year'])): $month = date('m'); $year = date('Y'); else: $month = $_GET['month']; $year = $_GET['year']; endif;?>
	<h1 class='page-title'>EMS Order Dashboard</h1>
	
	<form method='get' action='admin.php?page=ems-system'>
		<input type='hidden' name='page' value='ems-system' />
		<label>Month: </label> <select name='month' id='month'>
			<?php for($i=1;$i<=12;$i++): if($i<10): $pref = '0'; else: $pref = ''; endif;?>
		<option value='<?php echo $pref.$i;?>'><?php echo $pref.$i;?></option>
			<?php endfor;?>
			
		</select>
		<label>Year: </label> <select name='year' id='year'>
			<?php for($i=2016;$i<=2020;$i++): if($i<10): $pref = '0'; else: $pref = ''; endif;?>
		<option value='<?php echo $pref.$i;?>'><?php echo $pref.$i;?></option>
			<?php endfor;?>
			
		</select>
		
		<input type='submit' class='button-primary' value='Search these dates' />
		
	</form>
	
	  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <?php $counter = 0; $monthNum  = $month;
$dateObj   = DateTime::createFromFormat('!m', $monthNum);
$monthName = $dateObj->format('F'); // March ?>
  <h3>Sales for <?php echo $monthName;?> <?php echo $year;?></h3>
  <div id="chart_div"></div>
	



<script type='text/javascript'>

google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawBasic);

	<?php global $Quote; $number = cal_days_in_month(CAL_GREGORIAN, $month, $year); ?>
function drawBasic() {

     var data = google.visualization.arrayToDataTable([
        ['Day of month', 'Sales', { role: 'style' } ],
       <?php for($i=1;$i<=$number;$i++):
		 if($i<10):$pref = '0'; else: $pref = ''; endif;
		 $sqldate = $year.'-'.$month.'-'.$pref.$i;
		 $sales = $Quote->get_sales_for_date($sqldate);
		 $counter += $sales;
		 ?>
		 ['<?php echo $i;?>', <?php echo $sales;?>, 'color: #fb3c00'],
		 <?php endfor;?>
       
      ]);

      var chart = new google.visualization.ColumnChart(
        document.getElementById('chart_div'));

      chart.draw(data);
    }
	$ = jQuery;

	$(document).ready(function(){
		
		<?php if(isset($_GET['month'])):?>
		$('#month').val('<?php echo $_GET['month'];?>');
		<?php endif;?>
		
		<?php if(isset($_GET['year'])):?>
		$('#year').val('<?php echo $_GET['year'];?>');
		<?php endif;?>
		
		<?php if(!isset($_GET['month']) && !isset($_GET['year'])):
		$m = date('m'); $y = date('Y');
		?>
		$('#month').val('<?php echo $m;?>');
		$('#year').val('<?php echo $y;?>');
		<?php endif;?>
		
	});

</script>

	<h2>TOTAL SALES: <span style='color:#F00;'><?php echo $counter;?></span></h2>
	
	<hr />
	
	<div class='row-left'>
		
		<h2>EMS Users</h2>
		
		<table class='widefat'>
			
			<thead>
				<tr>
					<th>Name</th>
					<th>Email</th>
					
				</tr>
				
			</thead>
			<tfoot>
				<tr>
					<th>Name</th>
					<th>Email</th>
					
				</tr>
				
			</tfoot>
			<?php $users = $Quote->get_all_users();?>
			<tbody>
			<?php foreach($users as $user):?>
				<tr>
					<td><?php echo $user->first_name . ' ' . $user->last_name;?></td>
					<td><?php echo $user->email_address;?></td>
					
				</tr>
				<?php endforeach;?>
			</tbody>
			
		</table>
		
	</div>
	
	<div class='row-right'>
		
		<h2>Unresolved Shopping Carts</h2>
		
		<table class='widefat'>
			
			<thead>
				<tr>
					<th>Name</th>
					<th>Date</th>
					<th>View</th>
					
				</tr>
				
			</thead>
			<?php $carts = $Quote->get_unresolved_carts();?>
			<tbody>
				<?php foreach($carts as $cart): $d = strtotime($cart->date); $date = date('d/m/y', $d); ?><tr>
				<td><?php echo $cart->xuser_name;?></td>
				<td><?php echo $date;?></td>
				<td><a href='admin.php?page=ems_orders&view=<?php echo $cart->id;?>' class='button-primary'>View Shipment</a></td></tr>
				<?php endforeach;?>
				
			</tbody>
			
		</table>
		
	</div>
	
	<div class='clearfix'></div>

</div>