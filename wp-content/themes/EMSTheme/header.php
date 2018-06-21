<?php
/**
* The header for our theme.
*
* Displays all of the <head> section and everything up till <div id="content">
*
* @package understrap
*/
$container = get_theme_mod( 'understrap_container_type' ); global $Quote; 
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-title" content="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>">
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <?php wp_head(); ?>  
  <script type='text/javascript'>
    var base_currency = '<?php echo $Quote->currency;?>';
    var currency_symbol = '<?php echo $Quote->get_currency_symbol();?>';
    var ajax_url = '<?php bloginfo('home');?>/wp-admin/admin-ajax.php';
    var base_url = '<?php bloginfo('home');?>';
  </script>   
  <script>
    (function(i,s,o,g,r,a,m){
      i['GoogleAnalyticsObject']=r;
      i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)}
        ,i[r].l=1*new Date();
      a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];
      a.async=1;
      a.src=g;
      m.parentNode.insertBefore(a,m)
    }
    )(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-24362243-5', 'auto');
    ga('send', 'pageview');
  </script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
$(document).ready(function(){
   $("#itemWeight").change(function() {
	   var bagweight = $("#itemWeight").val();
	   var url = window.location.search.substr(1).split('&amp;');
	   var pro = url[3];
	   var protocol= pro.split('=');
       var pro_val = protocol[1];
       if(bagweight > 30){
		  var additional_weight= bagweight-30; 
		   if(pro_val=='standard'){
			var additional_price = additional_weight*2.20; 
		  }
		  else{
			var additional_price = additional_weight*4.40;   
		  }
		  $("#additional-weight-amount").css({ display: "" });
		  $("#additional-weight-value").css({ display: "" });
		  $("#additional-weight").html(additional_weight);
		  $("#selected-additional-amount").html(additional_price.toFixed(2));  
	   } 
	   else{
		 $("#additional-weight-amount").css({ display: "none" }); 
		 $("#additional-weight-value").css({ display: "none" });
	   }
   })       
})
</script>


<? /*
  <script type='text/javascript' src='<?php bloginfo('template_url');?>/js/jquery-3.1.1.min.js'></script>
*/ ?>
</head>
<body <?php body_class(); ?>>
<div class="hfeed site" id="page">
  <!-- ******************* The Navbar Area ******************* -->
  <div class="wrapper-fluid wrapper-navbar" id="wrapper-navbar">
    <a class="skip-link screen-reader-text sr-only" href="#content"><?php _e( 'Skip to content','understrap' ); ?></a>
    <div id="topnavbar">
      <div class="container">
        <ul class="nav justify-content-end">
          <?php global $Quote;
if($Quote->is_logged_in()):?>
          <li id="menu-item-18" class="menu-item nav-item"><a title="Login" href="<?php bloginfo('home');?>/my-account" class="nav-link">My Account</a></li>
          <li style="padding-top:.5em">|</li>
          <li id="menu-item-18" class="menu-item nav-item"><a title="Register" href="<?php bloginfo('home');?>?ems_cmd=logout" class="nav-link">Logout</a></li>
          <?php else:?>
          <li id="menu-item-18" class="menu-item nav-item"><a title="Login" href="<?php bloginfo('home');?>/login" class="nav-link">Login</a></li>
          <li style="padding-top:.5em">|</li>
          <li id="menu-item-18" class="menu-item nav-item"><a title="Register" href="<?php bloginfo('home');?>/register" class="nav-link">Register</a></li>
          <?php endif;?>
          <li style="padding-top:.5em">|</li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="currencyDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $Quote->get_currency_title();?></a>
            <div class="dropdown-menu" aria-labelledby="currencyDropdown">
              <a class="dropdown-item" href="?set_currency=GBP">£ British Pound</a>
              <a class="dropdown-item" href="?set_currency=EUR">€ Euro</a>
              <a class="dropdown-item" href="?set_currency=USD">$ US Dollar</a>
              <a class="dropdown-item" href="?set_currency=AUD">$ Australian Dollar</a>
              <a class="dropdown-item" href="?set_currency=SEK">kr Swedish Krona</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <nav id="primaryNav" class="navbar navbar-toggleable-md navbar-light">
      <?php if ( 'container' == $container ) : ?>
      <div class="container">
        <?php endif; ?>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Your site title as branding in the menu -->
        <?php if ( ! has_custom_logo() ) { ?>
        <?php if ( is_front_page() && is_home() ) : ?>
        <h1 class="navbar-brand mb-0"><?php bloginfo( 'name' ); ?></h1>
        <?php else : ?>
        <a class="navbar-brand" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
        <?php endif; ?>
        <?php } else {
the_custom_logo();
} ?><!-- end custom logo -->
        <!-- The WordPress Menu goes here -->
        <?php /*wp_nav_menu(
array(
'theme_location'  => 'primary',
'container_class' => 'collapse navbar-collapse',
'container_id'    => 'navbarNavDropdown',
'menu_class'      => 'navbar-nav',
'fallback_cb'     => '',
'menu_id'         => 'main-menu',
'walker'          => new WP_Bootstrap_Navwalker(),
)
); */ ?>
        <div id="navbarNavDropdown" class="collapse navbar-collapse">
          <ul id="main-menu" class="navbar-nav">
            <li id="menu-item-18" class="menu-item nav-item"><a title="Quote and Book" href="<?php bloginfo('home');?>/quote/" class="nav-link orangebutton">Quote &amp; Book</a></li>
            <li id="menu-item-18" class="menu-item nav-item"><a title="Contact Us" href="<?php bloginfo('home');?>/contact-us/" class="nav-link">Get In Touch</a></li>
            <li id="menu-item-18" class="menu-item nav-item"><a title="FAQ" href="<?php bloginfo('home');?>/faq/" class="nav-link">FAQ</a></li>
            <li id="menu-item-18" class="menu-item nav-item"><a title="Destinations" href="<?php bloginfo('home');?>/baggage-shipping-destinations/" class="nav-link">Destinations</a></li>
            <li id="menu-item-18" class="menu-item nav-item"><a title="How It Works" href="<?php bloginfo('home');?>/how-it-works/" class="nav-link">How It Works</a></li>
            <li id="menu-item-18" class="menu-item nav-item"><a title="How It Works" href="<?php bloginfo('home');?>" class="nav-link">Home</a></li>
          </ul>
        </div>
        <?php if ( 'container' == $container ) : ?>
      </div><!-- .container -->
      <?php endif; ?>
    </nav><!-- .site-navigation -->
  </div><!-- .wrapper-navbar end -->