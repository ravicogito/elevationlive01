<?php
/**
 * Template Name: My Account
 *
 *
 * @package understrap
 */

get_header();

global $Quote;



$container = get_theme_mod( 'understrap_container_type' );


session_start();

?>
<script type='text/javascript'>
		var ajax_url = '<?php bloginfo('home');?>/wp-admin/admin-ajax.php';
</script>
<div class="wrapper" id="full-width-page-wrapper">

	<div class="<?php echo esc_html( $container ); ?>" id="content">

		<div class="row">

			<div class="col-md-12 content-area" id="primary">

				<main class="site-main" id="main" role="main">
					
					<h2>My Account</h2>
					
					
					<header class="entry-header whitetextbox">
						<ul class="nav nav-pills nav-fill">
							<li class="nav-item order-progress"><a id='pill-step-1' data-step='1' class="nav-link <?php if(!isset($_GET['account'])):?>active<?php endif;?>"  href="<?php bloginfo('home');?>/my-account">Orders</a></li>
							<li class="nav-item order-progress"><a id='pill-step-2' data-step='2' class="nav-link <?php if(isset($_GET['account'])):?>active<?php endif;?> " href="<?php bloginfo('home');?>/my-account?account">Account</a></li>
							<li class="nav-item order-progress"><a id='pill-step-3' data-step='3' class="nav-link "  href="<?php bloginfo('home');?>/?ems_cmd=logout">Logout</a></li>
						</ul>
					</header><!-- .entry-header -->
					<?php if(!isset($_GET['account'])):?>
					<div class="entry-content">
        <div class="tab-content">
        	<section class="tab-pane active" id="orderStep1" role="tabpanel">
        	
        	<h3>My Orders</h3>
        	
        	<?php $my_orders = $Quote->get_my_orders();  ?>
        	
        	<?php if(empty($my_orders)):?>
        	<p>You currently have no shipments connected to your account. Click <a href='<?php bloginfo('home');?>/quote'>here</a> to start one.</p>
        	<?php else:?>
        	
        		<?php foreach($my_orders as $order):
				$collection_address = unserialize($order->collection_address);
				$delivery_address = unserialize($order->delivery_address);?>
        		
        		<div id='an-order' class='row'>
        			
        			<div class='col-md-6'>
        				
        				<h4>COLLECTION ADDRESS</h4>
        				
        			<?php	$address_type = $collection_address['addressType']; ?>
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
		
		<p><?php echo $collection_address['custAddress1'];?><br /><?php echo $collection_address['custAddress2'];?><br /><?php echo $collection_address['custCity'];?><br /><?php echo $collection_address['custPostcode'];?><br /><?php echo $collection_address['country'];?></p>
		
		<?php if(isset($collection_address['buzzer']) && $collection_address['buzzer'] != ''):?>
		<p><strong>Buzzer: </strong><?php echo $collection_address['buzzer'];?></p>
		<?php endif;?>
        				
        				
        			</div><!-- END LEFT -->
        			
        			<div class='col-md-6'>
        				
        				<h4>DELIVERY ADDRESS</h4>
        				
        				<?php  $address_type = $delivery_address['addressType']; ?>
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
		
		<p><?php echo $delivery_address['custAddress1'];?><br /><?php echo $delivery_address['custAddress2'];?><br /><?php echo $delivery_address['custCity'];?><br /><?php echo $delivery_address['custPostcode'];?><br /><?php echo $delivery_address['country'];?></p>
		
		<?php if(isset($delivery_address['buzzer']) && $delivery_address['buzzer'] != ''):?>
		<p><strong>Buzzer: </strong><?php echo $delivery_address['buzzer'];?></p>
		<?php endif;?>
        				
        				
        			</div><!-- END RIGHT -->
        			
        			<div class='clearfix'></div>
        			
        			<hr />
        			
        			
        			
        			<?php $parcels = json_decode($order->parcels);
					
					?>
       		
       		
       		<div class='col-md-12'><h4>PARCELS</h4>
       		<table class='table table-striped'>
		
		<thead>
			
			<tr>
				
				<th>Description</th>
				<th>Dimensions</th>
				<th>Weight</th>
				<th>Price</th>
				<th>Shipping Label</th>
				<th>Tracking Code</th>
				
			</tr>
			
		</thead>
		
		<tfoot>
			
			<tr>
				
				<th>Description</th>
				<th>Dimensions</th>
				<th>Weight</th>
				<th>Price</th>
				<th>Shipping Label</th>
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
				<td><?php if($found == 1):?>
				<a href='<?php echo $shipping_label;?>'>Click to view</a><?php else:?>WAITING LABEL<?php endif;?></td>
				<td><?php if($found == 1): echo $tracking_code; else: echo 'WAITING TRACKING CODE'; endif;?></td>
				
				
			</tr>
			<?php endforeach;?>
			
		</tbody>
		
				</table></div>
        		</div>
        		
        		<h3>Order Total: £<?php echo $order->total;?></h3>
	
	<table class='table table-striped'>
	<tr>
	<td><strong>Shipping Date:</strong><br /><?php $sd = strtotime($order->shipping_date); echo date('d/m/Y', $sd);?></td>
	
	<td><strong>Send Free Labels: </strong><br /><?php if($order->send_free_labels == 1):?>Yes<?php else:?>No<?php endif;?></td>
	
	<td><strong>Post Free Labels: </strong><br /><?php if($order->post_free_labels == 1):?>Yes<?php else:?>No<?php endif;?></td>
	
	<td><strong>Cancellation Cover: </strong><br /><?php if($order->cancellation_cover == 1):?>Yes<?php else:?>No<?php endif;?></td>
	
	<td><strong>Level of Cover: </strong><br />
	
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
		</td>
		</tr>
				</table><hr />
        		<?php endforeach;?>
        	
        	<?php endif;?>
        	
			</section>
				
						</div></div><!-- END MY ORDERS -->
						
						<?php else:?>
						<div class="entry-content">
        <div class="tab-content">
        	<section class="tab-pane active" id="orderStep1" role="tabpanel">
        	
        	<h2>My Account Details</h2>
        	
        	
        	<form method='post' id='user-account-form'>
        		
        		
        		<input type='hidden' name='ems_cmd' value='update_account' />
        		<?php session_start(); $user = $_SESSION['ems_user'];?>
        		<div class="form-group">
                            <label for="accountName">Your First Name *</label>
                            <input id="user-first-name" name="emsuser[first_name]" value="<?php echo $user->first_name;?>" type="text" class="form-control requser">
                        </div>
                        
                        <div class="form-group">
                            <label for="accountName">Your Last Name *</label>
                            <input id="user-last-name" name="emsuser[last_name]" value="<?php echo $user->last_name;?>" type="text" class="form-control requser">
                        </div>
                        
                       
                        <div class="form-group">
                            <label for="accountEmail">Email</label>
                            <input id="user-email" name="emsuser[email_address]" value="<?php echo $user->email_address;?>" type="email" class="form-control requser">
                        </div>
                      
                        <div class="form-group">
                            <label for="accountPassword">New Password *<small>ONLY FILL THIS OUT IF YOU WANT TO CHANGE IT</small></label>
                            <input id="user-password" name="emsuser[password]" type="password" class="form-control requser">
                        </div>
                        
                        <div class="form-group">
                            <label for="accountName">Confirm New Password *<small>ONLY FILL THIS OUT IF YOU WANT TO CHANGE IT</small></label>
                            <input id="user-password-confirm" type="password" class="form-control requser">
                        </div>
                        
                        <input type='submit' value='Update Account' class='btn btn-primary' />
        		
        	</form>
        	
        	
			</section></div></div>
						
						<?php endif;?>
					
				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .row end -->

	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
