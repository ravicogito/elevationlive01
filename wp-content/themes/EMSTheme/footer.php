<?php
/**
* The template for displaying the footer.
*
* Contains the closing of the #content div and all content after
*
* @package understrap
*/
$container = get_theme_mod( 'understrap_container_type' );
?>
<div class="wrapper" id="wrapper-footer">
  <div class="<?php echo esc_html( $container ); ?> whitetextbox">
    <div class="row">
      <div class="col-sm-6 col-md-3">
        <?php wp_nav_menu(
array(
'theme_location'  => 'footer1',
'container_class' => '',
'container_id'    => '',
'menu_class'      => '',
'fallback_cb'     => '',
'menu_id'         => 'footer-menu-1',
) ); ?>
      </div>
      <div class="col-sm-6 col-md-3">
        <?php wp_nav_menu(
array(
'theme_location'  => 'footer3',
'container_class' => '',
'container_id'    => '',
'menu_class'      => '',
'fallback_cb'     => '',
'menu_id'         => 'footer-menu-3',
) ); ?>
      </div>
      <div class="col-sm-6 col-md-3">
        <?php wp_nav_menu(
array(
'theme_location'  => 'footer2',
'container_class' => '',
'container_id'    => '',
'menu_class'      => '',
'fallback_cb'     => '',
'menu_id'         => 'footer-menu-2',
) ); ?>
      </div>
      <div class="col-sm-6 col-md-3">
        <?php get_sidebar( 'footer4' ); ?>
        <aside id="text-2" class="footer-social-widget widget widget_text">
          <h3 class="widget-title mt-3">Secured By Worldpay</h3>
          <div class="textwidget secure">
            <!--script language="JavaScript" src="https://secure.worldpay.com/wcc/logo?instId=1182871"></script-->
            <a target="_blank" href="http://www.mastercard.com"><img alt="Mastercard" src="<?php bloginfo('template_url'); ?>/images/WP_ECMC.gif"></a>
            <a target="_blank" href="http://www.jcbusa.com"><img alt="JCB" src="<?php bloginfo('template_url'); ?>/images/WP_JCB.png"></a>
            <a target="_blank" href="http://www.maestrocard.com"><img alt="Maestro" src="<?php bloginfo('template_url'); ?>/images/WP_MAESTRO.png"></a>
            <a target="_blank" href="http://www.paypal.com/"><img alt="Paypal" src="<?php bloginfo('template_url'); ?>/images/PAYPAL.png"></a>
            <a target="_blank" href="http://www.visa.com"><img alt="Visa Debit" src="<?php bloginfo('template_url'); ?>/images/WP_VISA_DELTA.png"></a>
            <a target="_blank" href="http://www.worldpay.com/support/index.php?CMP=BA22713"><img alt="Powered by WorldPay" src="<?php bloginfo('template_url'); ?>/images/poweredByWorldPay.gif"></a>
          </div>
        </aside>
      </div>
      <div class="col-md-12 mt-3">
        <footer class="site-footer" id="colophon">
          <div class="site-info">
            <?php printf( __( '&copy; Send My Stuff - All Rights Reserved', 'understrap' ) ); ?>
            <span class="sep"> | </span>
            <a href="<?php echo esc_url( __( 'https://wptweaks.co.uk/', 'understrap' ) ); ?>"><?php printf( __( 'WordPress Support &amp; Maintenance', 'understrap' ) ); ?></a>
          </div><!-- .site-info -->
        </footer><!-- #colophon -->
      </div><!--col end -->
    </div><!-- row end -->
  </div><!-- container end -->
</div><!-- wrapper end -->
</div><!-- #page -->
<link rel='stylesheet' href='<?php bloginfo('template_url');?>/css/app.css'>
<link rel='stylesheet' href='<?php bloginfo('template_url');?>/css/font.css'>

<?php get_sidebar( 'footerfull' ); ?>
<?php wp_footer(); ?>
<script  type='text/javascript' src='<?php bloginfo('template_url');?>/js/app.js'></script>
</body>
</html>