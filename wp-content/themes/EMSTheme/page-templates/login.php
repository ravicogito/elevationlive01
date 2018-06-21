<?php
/**
 * Template Name: Login Page
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
					
					<h2>Log In</h2>
					
					<div class='row'>
						
						<div class='col-md-4 col-md-offset-4'>
					<form method='post' action='<?php bloginfo('home');?>/my-account' id='login-form'>
						
						 <div id='login-msg' class='alert alert-danger' style='display:none;'></div>
                    	<div class="form-group">
                            <label for="accountEmailLogin">Email</label>
                            <input id="login-email" name="login[email_address]" type="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="accountPasswordLogin">Password</label>
                            <input id="login-password" name="login[password]" type="password" class="form-control">
                        </div>
                        <a href="Javascript:void(0);" id="do-login-page" class="btn btn-primary">Login</a>
                      
						
						
					</form><!-- END LOGIN FORM -->
						</div></div>
				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .row end -->

	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
