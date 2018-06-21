<?php
/**
 * Template Name: Buy Shopping
 *
 * Template for EMS Worldpay Integration
 *
 * @package understrap
 */
get_header();
$container = get_theme_mod('understrap_container_type');
?>

<div class="wrapper" id="full-width-page-wrapper">
    <div class="<?php echo esc_html($container); ?>" id="content">
        <div class="row">
            <div class="col-md-12 content-area" id="primary">
                <section class="tab-pane active" id="orderStep1">
                    <?php
                    $options = unserialize($Quote->get_shopping_option('shopping_options'));
                    $package = $_GET['package'];
                    $chosen_package = $options[$package];
                    $extra_packages = $_GET['extra_packages'];
                    $price_per_package = $Quote->get_shopping_option('price_per_shopping_package');
                    $price = $chosen_package['price'];
                    $extra_price = $extra_packages * $price_per_package;
                    $total_price = $price + $extra_price;
                    ?>
                    <input type='hidden' id='package-name' value='<?php echo $chosen_package['name']; ?>' />
                    <input type='hidden' id='package-index' value='<?php echo $package; ?>' />
                    <input type='hidden' id='package-amount' value='<?php echo $chosen_package['price']; ?>' />
                    <input type='hidden' id='ems-session' value='SHOP<?php echo $_SESSION['ems_session']; ?>' />
                    <input type='hidden' id='extra-packages' value='<?php echo $_GET['extra_packages']; ?>' />
                    <!-- START STEP 1 -->

                    <?php if (!$Quote->is_logged_in()): ?>
                        <div class="lightgreytextboxlp">
                            <h1>Register / Log In</h1>
                            <p>Please register for Express My Stuff or Log In using your existing account:</p>
                            <div id='shopping-register-login'>
                                <div id='shopping-register'>
                                    <div id='register-msg'></div>
                                    <div class='cover'><img src='<?php bloginfo('template_url'); ?>/images/spinner.gif' /></div>
                                    <h2>REGISTER</h2>
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
                                    <a href="Javascript:void(0);" class="btn btn-primary" id="do-shopping-register">Register</a>
                                </div><!-- END REGISTER -->

                                <div id='shopping-login'>
                                    <div id='login-msg'></div>
                                    <div class='cover'><img src='<?php bloginfo('template_url'); ?>/images/spinner.gif' /></div>
                                    <h2>LOGIN</h2>
                                    <div class="form-group">
                                        <label for="accountEmailLogin">Email</label>
                                        <input id="login-email" type="email" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="accountPasswordLogin">Password</label>
                                        <input id="login-password" type="password" class="form-control">
                                    </div>
                                    <a href="Javascript:void(0);" id="do-shopping-login" class="btn btn-primary">Login</a>
                                </div><!-- END SHOPPING LOGIN -->
                                <div class='clearfix'></div>
                            </div><!-- END REGISTER LOGIN -->
                        </div>
                    <?php endif; ?>
                    <?php session_start();
                    $user = $_SESSION['ems_user'];
                    $user_id = $user->id;
                    if ($user_id != ''): ?>
                        <input type='hidden' id='shopping-user-id' value='<?php echo $user_id; ?>' />
                    <?php else: ?>
                        <input type='hidden' id='shopping-user-id' />
                        <?php endif; ?>
                    <div id='shopping-buy' <?php if ($user_id != ''): ?>style='display:block;'<?php endif; ?>>
                <?php include('shopping-address.php'); ?>
                    </div><!-- END SHOPPING BUY -->
                </section>

                <main class="site-main" id="main" role="main">
                    <form action="https://secure.worldpay.com/wcc/purchase" id='shopping-worldpay' name="BuyForm" method="POST">
                        <input type="hidden" name="instId"  value="1182871"><!-- The "instId" value "211616" should be replaced with the Merchant's own installation Id --> 
                        <input type="hidden" name="cartId"  value="SHOP<?php echo $_SESSION['ems_session']; ?>">
                        <input type="hidden" name="currency" value="GBP"><!-- Choose appropriate currency that you would like to use -->
                        <input type="hidden" name="amount"  value="<?php echo $total_price; ?>">
                        <input type="hidden" name="desc" value="EMS Shopping Experience: <?php echo $chosen_package['name']; ?>">
                        <!-- JavaScript is used to give functionality to some of the pages elements. -->
                        <br>
                        <br>
                        <input type='hidden' class='btn btn-primary btn-lg text-uppercase' value='Buy Now With WorldPay' />
                    </form>
                </main><!-- #main -->
            </div><!-- #primary -->
        </div><!-- .row end -->
    </div><!-- Container end -->
</div><!-- Wrapper end -->

<script type='text/javascript'>
    window.onload = function () {
        //document.getElementById('bbg').submit();
    }
</script>

<?php get_footer(); ?>
