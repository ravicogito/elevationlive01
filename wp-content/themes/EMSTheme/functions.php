<?php
/**
 * Understrap functions and definitions
 *
 * @package understrap
 */

/**
 * Theme setup and custom theme supports.
 */
require get_template_directory() . '/inc/setup.php';

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Load functions to secure your WP install.
 */
require get_template_directory() . '/inc/security.php';

/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/inc/enqueue.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/pagination.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/custom-comments.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load custom WordPress nav walker.
 */
require get_template_directory() . '/inc/bootstrap-wp-navwalker.php';

/**
 * Load WooCommerce functions.
 */
require get_template_directory() . '/inc/woocommerce.php';

/**
 * Load Editor functions.
 */
require get_template_directory() . '/inc/editor.php';

// Custom Scripting to Move JavaScript from the Head to the Footer

function remove_head_scripts() { 
   remove_action('wp_head', 'wp_print_scripts'); 
   remove_action('wp_head', 'wp_print_head_scripts', 9); 
   remove_action('wp_head', 'wp_enqueue_scripts', 1);

   add_action('wp_footer', 'wp_print_scripts', 5);
   add_action('wp_footer', 'wp_enqueue_scripts', 5);
   add_action('wp_footer', 'wp_print_head_scripts', 5); 
} 
add_action( 'wp_enqueue_scripts', 'remove_head_scripts' );

// END Custom Scripting to Move JavaScript

add_action('wp_enqueue_scripts', 'rudr_move_jquery_to_footer');  
 
function rudr_move_jquery_to_footer() {  
 	// unregister the jQuery at first
        wp_deregister_script('jquery');  
 
        // register to footer (the last function argument should be true)
        wp_register_script('jquery', includes_url('/js/jquery/jquery.js'), false, null, true);  
 
        wp_enqueue_script('jquery');  
}

add_action('wp_ajax_do_promocode_chk', 'do_promocode_chk');
add_action('wp_ajax_nopriv_do_promocode_chk', 'do_promocode_chk');

function do_promocode_chk() {
    global $wpdb;
    $recArrp                 = array();
    $promo_code              = $_POST['promo_code'];
	$price              	 = $_POST['price'];
    
	if(!empty($promo_code))
	{
		$sqlpromo          		= "SELECT * FROM `ems_promocode` WHERE `code`='$promo_code'";
		$promorArr              = $wpdb->get_results($sqlpromo,'ARRAY_A');
		$recArrp['discount']    = $promorArr[0]['discount'];
		$amt                    = $price*$recArrp['discount']/100;
		$amt_final              = $price-$amt; 
		$recArrp['price']    	= number_format($amt_final,2);
		$recArrp['msg']			= "Promo Code applied successfully";
		$recArrp['process']		= "success";
	}
	else {
			$recArrp['discount']	= 0;
			$recArrp['price']    	= $price;
			$recArrp['msg']			= "";
			$recArrp['process']		= "fail";
		}
		$_SESSION['discount'] = $recArrp['discount'];
	
   echo json_encode($recArrp);
   exit;
   
}


function postcode_pattern_array($countryCode){

  //**************************************************
    // List of array with post code 
    //*************************************************
    
    $postCode =   array(
                  0 =>  array("cty_code" => "GB", "cty_postcode" => '#^(GIR ?0AA|[A-PR-UWYZ]([0-9]{1,2}|([A-HK-Y][0-9]([0-9ABEHMNPRV-Y])?)|[0-9][A-HJKPS-UW]) ?[0-9][ABD-HJLNP-UW-Z]{2})$#'),
                  1 =>  array("cty_code" => "JE", "cty_postcode" => "JEd[dA-Z]?[ ]?d[ABD-HJLN-UW-Z]{2}"),
                  2 =>  array("cty_code" => "GG", "cty_postcode" => "GYd[dA-Z]?[ ]?d[ABD-HJLN-UW-Z]{2}"),
                  3 =>  array("cty_code" => "IM", "cty_postcode" => "IMd[dA-Z]?[ ]?d[ABD-HJLN-UW-Z]{2}"),
                  4 =>  array("cty_code" => "US", "cty_postcode" => "d{5}([ -]d{4})?"),
                  5 =>  array("cty_code" => "CA", "cty_postcode" => "[ABCEGHJKLMNPRSTVXY]d[ABCEGHJ-NPRSTV-Z][ ]?d[ABCEGHJ-NPRSTV-Z]d"),
                  6 =>  array("cty_code" => "DE", "cty_postcode" => "d{5}"),
                  7 =>  array("cty_code" => "JP", "cty_postcode" => "d{3}-d{4}"),
                  8 =>  array("cty_code" => "FR", "cty_postcode" => "d{2}[ ]?d{3}"),
                  9 =>  array("cty_code" => "AU", "cty_postcode" => "d{4}"),
                  10 =>  array("cty_code" => "IT", "cty_postcode" => "d{5}"),
                  11 =>  array("cty_code" => "CH", "cty_postcode" => "d{4}"),
                  12 =>  array("cty_code" => "AT", "cty_postcode" => "d{4}"),
                  13 =>  array("cty_code" => "ES", "cty_postcode" => "d{5}"),
                  14 =>  array("cty_code" => "NL", "cty_postcode" => "d{4}[ ]?[A-Z]{2}"),
                  15 =>  array("cty_code" => "BE", "cty_postcode" => "d{4}"),
                  16 =>  array("cty_code" => "DK", "cty_postcode" => "d{4}"),
                  17 =>  array("cty_code" =>"SE", "cty_postcode" => "d{3}[ ]?d{2}"),
                  18 =>  array("cty_code" => "NO", "cty_postcode" => "d{4}"),
                  19 =>  array("cty_code" => "BR", "cty_postcode" => "d{5}[-]?d{3}"),
                  20 =>  array("cty_code" => "PT", "cty_postcode" => "d{4}([-]d{3})?"),
                  21 =>  array("cty_code" => "FI", "cty_postcode" => "d{5}"),
                  22 =>  array("cty_code" => "AX", "cty_postcode" => "22d{3}"),
                  23 =>  array("cty_code" => "KR", "cty_postcode" => "d{3}[-]d{3}"),
                  24 =>  array("cty_code" => "CN", "cty_postcode" => "d{6}"),
                  25 =>  array("cty_code" => "TW", "cty_postcode" => "d{3}(d{2})?"),
                  26 =>  array("cty_code" => "SG", "cty_postcode" => "d{6}"),
                  27 =>  array("cty_code" => "DZ", "cty_postcode" => "d{5}"),
                  28 =>  array("cty_code" => "AD", "cty_postcode" => "ADd{3}"),
                  29 =>  array("cty_code" =>"AR", "cty_postcode" => "([A-HJ-NP-Z])?d{4}([A-Z]{3})?"),
                  30 =>  array("cty_code" =>"AM", "cty_postcode" => "(37)?d{4}"),
                  31 =>  array("cty_code" =>"AZ", "cty_postcode" => "d{4}"),
                  32 =>  array("cty_code" =>"BH", "cty_postcode" => "((1[0-2]|[2-9])d{2})?"),
                  33 =>  array("cty_code" =>"BD", "cty_postcode" => "d{4}"),
                  34 =>  array("cty_code" =>"BB", "cty_postcode" => "(BBd{5})?"),
                  35 =>  array("cty_code" =>"BY", "cty_postcode" => "d{6}"),
                  36 =>  array("cty_code" =>"BM", "cty_postcode" => "[A-Z]{2}[ ]?[A-Z0-9]{2}"),
                  37 =>  array("cty_code" =>"BA", "cty_postcode" => "d{5}"),
                  38 =>  array("cty_code" =>"IO", "cty_postcode" => "BBND 1ZZ"),
                  39 =>  array("cty_code" =>"BN", "cty_postcode" => "[A-Z]{2}[ ]?d{4}"),
                  40 =>  array("cty_code" =>"BG", "cty_postcode" => "d{4}"),
                  41 =>  array("cty_code" =>"KH", "cty_postcode" => "d{5}"),
                  42 =>  array("cty_code" =>"CV", "cty_postcode" => "d{4}"),
                  43 =>  array("cty_code" =>"CL", "cty_postcode" => "d{7}"),
                  44 =>  array("cty_code" =>"CR", "cty_postcode" => "d{4,5}|d{3}-d{4}"),
                  44 =>  array("cty_code" =>"HR", "cty_postcode" => "d{5}"),
                  45 =>  array("cty_code" =>"CY", "cty_postcode" => "d{4}"),
                  46 =>  array("cty_code" =>"CZ", "cty_postcode" => "d{3}[ ]?d{2}"),
                  47 =>  array("cty_code" =>"DO", "cty_postcode" => "d{5}"),
                  48 =>  array("cty_code" =>"EC", "cty_postcode" => "([A-Z]d{4}[A-Z]|(?:[A-Z]{2})?d{6})?"),
                  49 =>  array("cty_code" =>"EG", "cty_postcode" => "d{5}"),
                  50 =>  array("cty_code" =>"EE", "cty_postcode" => "d{5}"),
                  51 =>  array("cty_code" =>"FO", "cty_postcode" => "d{3}"),
                  52 =>  array("cty_code" =>"GE", "cty_postcode" => "d{4}"),
                  53 =>  array("cty_code" =>"GR", "cty_postcode" => "d{3}[ ]?d{2}"),
                  54 =>  array("cty_code" =>"GL", "cty_postcode" => "39d{2}"),
                  55 =>  array("cty_code" =>"GT", "cty_postcode" => "d{5}"),
                  56 =>  array("cty_code" =>"HT", "cty_postcode" => "d{4}"),
                  57 =>  array("cty_code" =>"HN", "cty_postcode" => "(?:d{5})?"),
                  58 =>  array("cty_code" =>"HU", "cty_postcode" => "d{4}"),
                  59 =>  array("cty_code" =>"IS", "cty_postcode" => "d{3}"),
                  60 =>  array("cty_code" =>"IN", "cty_postcode" => "/^[0-9]{6}$/"),
                  61 =>  array("cty_code" =>"ID", "cty_postcode" => "d{5}"),
                  62 =>  array("cty_code" =>"IL", "cty_postcode" => "d{5}"),
                  63 =>  array("cty_code" =>"JO", "cty_postcode" => "d{5}"),
                  64 =>  array("cty_code" =>"KZ", "cty_postcode" => "d{6}"),
                  65 =>  array("cty_code" =>"KE", "cty_postcode" => "d{5}"),
                  66 =>  array("cty_code" =>"KW", "cty_postcode" => "d{5}"),
                  67 =>  array("cty_code" =>"LA", "cty_postcode" => "d{5}"),
                  68 =>  array("cty_code" =>"LV", "cty_postcode" => "d{4}"),
                  69 =>  array("cty_code" =>"LB", "cty_postcode" => "(d{4}([ ]?d{4})?)?"),
                  70 =>  array("cty_code" =>"LI", "cty_postcode" => "(948[5-9])|(949[0-7])"),
                  71 =>  array("cty_code" =>"LT", "cty_postcode" => "d{5}"),
                  72 =>  array("cty_code" =>"LU", "cty_postcode" => "d{4}"),
                  73 =>  array("cty_code" =>"MK", "cty_postcode" => "d{4}"),
                  74 =>  array("cty_code" =>"MY", "cty_postcode" => "d{5}"),
                  75 =>  array("cty_code" =>"MV", "cty_postcode" => "d{5}"),
                  76 =>  array("cty_code" =>"MT", "cty_postcode" => "[A-Z]{3}[ ]?d{2,4}"),
                  77 =>  array("cty_code" =>"MU", "cty_postcode" => "(d{3}[A-Z]{2}d{3})?"),
                  78 =>  array("cty_code" =>"MX", "cty_postcode" => "d{5}"),
                  79 =>  array("cty_code" =>"MD", "cty_postcode" => "d{4}"),
                  80 =>  array("cty_code" =>"MC", "cty_postcode" => "980d{2}"),
                  81 =>  array("cty_code" =>"MA", "cty_postcode" => "d{5}"),
                  82 =>  array("cty_code" =>"NP", "cty_postcode" => "d{5}"),
                  83 =>  array("cty_code" =>"NZ", "cty_postcode" => "d{4}"),
                  84 =>  array("cty_code" =>"NI", "cty_postcode" => "((d{4}-)?d{3}-d{3}(-d{1})?)?"),
                  85 =>  array("cty_code" =>"NG", "cty_postcode" => "(d{6})?"),
                  86 =>  array("cty_code" =>"OM", "cty_postcode" => "(PC )?d{3}"),
                  87 =>  array("cty_code" =>"PK", "cty_postcode" => "d{5}"),
                  88 =>  array("cty_code" =>"PY", "cty_postcode" => "d{4}"),
                  89 =>  array("cty_code" =>"PH", "cty_postcode" => "d{4}"),
                  90 =>  array("cty_code" =>"PL", "cty_postcode" => "d{2}-d{3}"),
                  91 =>  array("cty_code" =>"PR", "cty_postcode" => "00[679]d{2}([ -]d{4})?"),
                  92 =>  array("cty_code" =>"RO", "cty_postcode" => "d{6}"),
                  93 =>  array("cty_code" =>"RU", "cty_postcode" => "d{6}"),
                  94 =>  array("cty_code" =>"SM", "cty_postcode" => "4789d"),
                  95 =>  array("cty_code" =>"SA", "cty_postcode" => "d{5}"),
                  96 =>  array("cty_code" =>"SN", "cty_postcode" => "d{5}"),
                  97 =>  array("cty_code" =>"SK", "cty_postcode" => "d{3}[ ]?d{2}"),
                  98 =>  array("cty_code" =>"SI", "cty_postcode" => "d{4}"),
                  99 =>  array("cty_code" =>"ZA", "cty_postcode" => "d{4}"),
                  100 =>  array("cty_code" => "LK", "cty_postcode" => "d{5}"),
                  101 =>  array("cty_code" => "TJ", "cty_postcode" => "d{6}"),
                  102 =>  array("cty_code" => "TH", "cty_postcode" => "d{5}"),
                  103 =>  array("cty_code" => "TN", "cty_postcode" => "d{4}"),
                  104 =>  array("cty_code" => "TR", "cty_postcode" => "d{5}"),
                  105 =>  array("cty_code" => "TM", "cty_postcode" => "d{6}"),
                  106 =>  array("cty_code" => "UA", "cty_postcode" => "d{5}"),
                  107 =>  array("cty_code" =>"UY", "cty_postcode" => "d{5}"),
                  108 =>  array("cty_code" => "UZ", "cty_postcode" => "d{6}"),
                  109 =>  array("cty_code" => "VA", "cty_postcode" => "00120"),
                  110 =>  array("cty_code" => "VE", "cty_postcode" => "d{4}"),
                  111 =>  array("cty_code" =>"ZM", "cty_postcode" => "d{5}"),
                  112 =>  array("cty_code" =>"AS", "cty_postcode" => "96799"),
                  113 =>  array("cty_code" =>"CC", "cty_postcode" => "6799"),
                  114 =>  array("cty_code" =>"CK", "cty_postcode" => "d{4}"),
                  115 =>  array("cty_code" =>"RS", "cty_postcode" => "d{6}"),
                  116 =>  array("cty_code" =>"ME", "cty_postcode" => "8d{4}"),
                  117 =>  array("cty_code" =>"CS", "cty_postcode" => "d{5}"),
                  118 =>  array("cty_code" =>"YU", "cty_postcode" => "d{5}"),
                  119 =>  array("cty_code" =>"CX", "cty_postcode" => "6798"),
                  120 =>  array("cty_code" =>"ET", "cty_postcode" => "d{4}"),
                  121 =>  array("cty_code" =>"FK", "cty_postcode" => "FIQQ 1ZZ"),
                  122 =>  array("cty_code" =>"NF", "cty_postcode" => "2899"),
                  123 =>  array("cty_code" =>"FM", "cty_postcode" => "(9694[1-4])([ -]d{4})?"),
                  124 =>  array("cty_code" =>"GF", "cty_postcode" => "9[78]3d{2}"),
                  125 =>  array("cty_code" =>"GN", "cty_postcode" => "d{3}"),
                  126 =>  array("cty_code" =>"GP", "cty_postcode" => "9[78][01]d{2}"),
                  127 =>  array("cty_code" =>"GS", "cty_postcode" => "SIQQ 1ZZ"),
                  128 =>  array("cty_code" =>"GU", "cty_postcode" => "969[123]d([ -]d{4})?"),
                  129 =>  array("cty_code" =>"GW", "cty_postcode" => "d{4}"),
                  130 =>  array("cty_code" =>"HM", "cty_postcode" => "d{4}"),
                  131 =>  array("cty_code" =>"IQ", "cty_postcode" => "d{5}"),
                  132 =>  array("cty_code" =>"KG", "cty_postcode" => "d{6}"),
                  133 =>  array("cty_code" =>"LR", "cty_postcode" => "d{4}"),
                  134 =>  array("cty_code" =>"LS", "cty_postcode"=> "d{3}"),
                  135 =>  array("cty_code" =>"MG", "cty_postcode" => "d{3}"),
                  136 =>  array("cty_code" =>"MH", "cty_postcode" => "969[67]d([ -]d{4})?"),
                  137 =>  array("cty_code" =>"MN", "cty_postcode" => "d{6}"),
                  138 =>  array("cty_code" =>"MP", "cty_postcode" => "9695[012]([ -]d{4})?"),
                  139 =>  array("cty_code" =>"MQ", "cty_postcode" => "9[78]2d{2}"),
                  140 =>  array("cty_code" =>"NC", "cty_postcode" => "988d{2}"),
                  141 =>  array("cty_code" =>"NE", "cty_postcode" => "d{4}"),
                  142 =>  array("cty_code" =>"VI", "cty_postcode" => "008(([0-4]d)|(5[01]))([ -]d{4})?"),
                  143 =>  array("cty_code" =>"PF", "cty_postcode" => "987d{2}"),
                  144 =>  array("cty_code" =>"PG", "cty_postcode" => "d{3}"),
                  145 =>  array("cty_code" =>"PM", "cty_postcode" => "9[78]5d{2}"),
                  146 =>  array("cty_code" =>"PN", "cty_postcode" => "PCRN 1ZZ"),
                  147 =>  array("cty_code" =>"PW", "cty_postcode" => "96940"),
                  148 =>  array("cty_code" =>"RE", "cty_postcode" => "9[78]4d{2}"),
                  149 =>  array("cty_code" =>"SH", "cty_postcode" => "(ASCN|STHL) 1ZZ"),
                  150 =>  array("cty_code" =>"SJ", "cty_postcode" => "d{4}"),
                  151 =>  array("cty_code" =>"SO", "cty_postcode" => "d{5}"),
                  152 =>  array("cty_code" =>"SZ", "cty_postcode" => "[HLMS]d{3}"),
                  153 =>  array("cty_code" =>"TC", "cty_postcode" => "TKCA 1ZZ"),
                  154 =>  array("cty_code" =>"WF", "cty_postcode" => "986d{2}"),
                  156 =>  array("cty_code" =>"XK", "cty_postcode" => "d{5}"),
                  157 =>  array("cty_code" =>"YT", "cty_postcode" => "976d{2}")
    );

    //*********************************************************************************
    // COLLECTTION CODE 
    //*********************************************************************************

     $array_key = array_search($countryCode, array_column($postCode, 'cty_code'));

     $pattern = (string)$postCode[$array_key]["cty_postcode"];

     return $pattern;

}

add_action('wp_ajax_do_postcode_chk_collection', 'do_postcode_chk_collection');
add_action('wp_ajax_nopriv_do_postcode_chk_collection', 'do_postcode_chk_collection');

function do_postcode_chk_collection() {
    global $wpdb;
    $recArr                 = array();
    $fromCountry            = $_POST['from'];
    $toCountry              = $_POST['to'];
    $collection_code        = $_POST['collection_code'];
      

    //*********************FROM COUNTRY **************************************
    $sqlItem                = "SELECT * FROM `ems_countries` WHERE `id`=$fromCountry";
    $itemArr                = $wpdb->get_results($sqlItem,'ARRAY_A');
    $recArr['postcode']     = $itemArr[0]['postcode'];
    $recArr['country_code'] = $itemArr[0]['country_code'];

    //*********************************************************************

         /* if($recArr['country_code']=="GB"){
                switch($collection_code){

                    case (preg_match($collection_code, '/^[A-Z]{1,2}[0-9][0-9A-Z]? ?[0-9][A-Z]{2}$/')):
                    $recArr['country_postcode_pattern'] = "1";
                    break;

                    case (preg_match($collection_code, '[A-Z][0-9][0-9]  [0-9][A-Z][A-Z]')):
                    $recArr['country_postcode_pattern'] = "1";
                    break;

                    case (preg_match($collection_code,'[A-Z][A-Z][0-9]  [0-9][A-Z][A-Z]')):
                    $recArr['country_postcode_pattern'] = "1";
                    break;

                    case (preg_match($collection_code,'[A-Z][A-Z][0-9][0-9]  [0-9][A-Z][A-Z]')):
                    $recArr['country_postcode_pattern'] = "1";
                    break;

                    case (preg_match($collection_code,'[A-Z][0-9][A-Z]  [0-9][A-Z][A-Z]')):
                    $recArr['country_postcode_pattern'] = "1";
                    break;

                    case (preg_match($collection_code,'/^[a-zA-Z]{1,2}([0-9]{1,2}|[0-9][a-zA-Z])s*[0-9][a-zA-Z]{2}$/')):
                    $recArr['country_postcode_pattern'] = "1";
                    break;

                    case ($collection_code == 'A9 9AA'):
                    $recArr['country_postcode_pattern'] = "1";
                    break;

                    case ($collection_code == 'A9 9AA'):
                    $recArr['country_postcode_pattern'] = "1";
                    break;

                    case ($collection_code == 'A9A 9AA'):
                    $recArr['country_postcode_pattern'] = "1";
                    break;

                    case ($collection_code == 'AA9 9AA'):
                    $recArr['country_postcode_pattern'] = "1";
                    break;

                    case ($collection_code == 'AA99 9AA'):
                    $recArr['country_postcode_pattern'] = "1";
                    break;

                    case ($collection_code == 'AA9A 9AA'):
                    $recArr['country_postcode_pattern'] = "1";
                    break;

                    default:
                    $recArr['country_postcode_pattern'] = "0";
                }


            } else{*/

                      $pattern = postcode_pattern_array($recArr['country_code']);

                      if (preg_match($pattern,  (string)$collection_code)) {
                         $recArr['country_postcode_pattern'] = "1";
                        }
                      else {
                             $recArr['country_postcode_pattern'] = "2";
                          }

            //}
 

        $recArr['country_postcode_patt'] = $pattern;
        $recArr['country_pcode'] = $collection_code;

        $recArr['toCountry'] = $toCountry;
         $recArr['fromCountry'] = $fromCountry;



       echo json_encode($recArr);
       exit;

        //****************************************************
        //END
        //*******************************************************
   
}

add_action('wp_ajax_do_postcode_chk_destination', 'do_postcode_chk_destination');
add_action('wp_ajax_nopriv_do_postcode_chk_destination', 'do_postcode_chk_destination');

function do_postcode_chk_destination() {
    global $wpdb;
    $recArr                 = array();
    $toCountry              = $_POST['to'];
    $destination_code        = $_POST['destination_code'];
    
    //*********************TO COUNTRY **************************************
    $sqlItemto                = "SELECT * FROM `ems_countries` WHERE `id`=$toCountry";
    $itemArrTo                = $wpdb->get_results($sqlItemto,'ARRAY_A');
    $recArr['postcode_des']     = $itemArrTo[0]['postcode'];
    $recArr['country_code_des'] = $itemArrTo[0]['country_code'];

    //*********************************************************************
   
          $pattern = postcode_pattern_array($recArr['country_code_des']);

          if (preg_match($pattern,  (string)$destination_code)) {
             $recArr['country_postcode_pattern'] = "1";
            }
          else {
                 $recArr['country_postcode_pattern'] = "2";
              }

         
 

        $recArr['country_postcode_patt'] = $pattern;
        $recArr['country_pcode'] = $destination_code;

        $recArr['toCountry'] = $toCountry;
         $recArr['fromCountry'] = $fromCountry;


       echo json_encode($recArr);
       exit;

        //****************************************************
        //END
        //*******************************************************
   
}

add_action('wp_ajax_do_promocode_chk', 'do_promocode_chk');
add_action('wp_ajax_nopriv_do_promocode_chk', 'do_promocode_chk');

// if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
// function my_jquery_enqueue() {
//    wp_deregister_script('jquery');
//    wp_register_script('jquery', "https://code.jquery.com/jquery-3.1.1.min.js", false, null);
//    wp_enqueue_script('jquery');
// }