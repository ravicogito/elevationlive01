<?php
/**
 * Template Name: Stripe Success Page
 *
 * Template for EMS Worldpay Integration
 *
 * @package understrap
 */
get_header();
$container = get_theme_mod('understrap_container_type');
$amount  =   number_format((float)$_GET['amount'] / 100, 2, '.', '');;
$package =   $_GET['package'];
?>

<div class="wrapper" id="full-width-page-wrapper">
    <div class="<?php echo esc_html($container); ?>" id="content">
        <div class="row">
            <div class="col-md-12 content-area" id="primary">
                <section class="tab-pane active" id="orderStep1">
                    <strong>Thank you for purchasing your shipping with Express My Stuff.</strong><br><br>
                    <strong>Your Order Detail is:</strong><br>
                    <b>Amount  = £ <?php echo $amount; ?></b><br>
                    <b>Package = <?php echo $package; ?></b>
                </section>
            </div>
        </div>
    </div>
</div>

<script type='text/javascript'>
    window.onload = function () {
        //document.getElementById('bbg').submit();
    }
</script>

<?php get_footer(); ?>
