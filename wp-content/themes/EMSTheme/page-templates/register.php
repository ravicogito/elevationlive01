<?php
/**
 * Template Name: Register Page
 *
 *
 * @package understrap
 */

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>
<script type='text/javascript'>
		var ajax_url = '<?php bloginfo('home');?>/wp-admin/admin-ajax.php';
</script>
<div class="wrapper" id="full-width-page-wrapper">

	<div class="<?php echo esc_html( $container ); ?>" id="content">

		<div class="row">

			<div class="col-md-12 content-area" id="primary">

				<main class="site-main" id="main" role="main">
					
					<h2>Register</h2>
					
					<div class='row'>
						
						<div class='col-md-12'>
					   <div id='register-msg' class='alert alert-danger' style='display:none;'></div>
                   <div id='register-success' class='alert alert-success' style='display:none;'>Your account has been registered! Click <a href='<?php bloginfo('home');?>/login'>here</a> to log in</div>
                    
                    	<div class="form-group">
                            <label for="accountName">Your First Name *</label>
                            <input id="user-first-name" name="emsuser[first_name]" type="text" class="form-control requser">
                        </div>
                        
                        <div class="form-group">
                            <label for="accountName">Your Last Name *</label>
                            <input id="user-last-name" name="emsuser[last_name]" type="text" class="form-control requser">
                        </div>
                        
                       
                        <div class="form-group">
                            <label for="accountEmail">Email</label>
                            <input id="user-email" name="emsuser[email_address]" type="email" class="form-control requser">
                        </div>
                      
                        <div class="form-group">
                            <label for="accountPassword">Password *</label>
                            <input id="user-password" name="emsuser[password]" type="password" class="form-control requser">
                        </div>
                        
                        <div class="form-group">
                            <label for="accountName">Confirm Password *</label>
                            <input id="user-password-confirm" type="text" class="form-control requser">
                        </div>
                        
                        <a href="Javascript:void(0);" class="btn btn-primary" id="do-register-page">Register</a>
						</div></div>
				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .row end -->

	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
