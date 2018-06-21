<?php
/**
 * Template Name: Payment Page
 *
 * Template for EMS Worldpay Integration
 *
 * @package understrap
 */
get_header();
$container = get_theme_mod('understrap_container_type');

require_once( dirname(__FILE__) . '/stripe-php/init.php' );
require_once( dirname(__FILE__) . '/stripe-php/config.php' );

global $wpdb;
global $Quote;
$publishable_key    =   PUBLIC_KEY;

$ems_session        =   get_ems_session();
$order              =   $Quote->get_order_from_session($ems_session);

$discount           =   $_SESSION['discount'];
$collection_address =   $order->collection_address;
$delivery_address   =   $order->delivery_address;                    
$parcelArray        =   $order->parcels;
$dimension          =   $parcelArray[0]->dimensions;
$weight             =   $parcelArray[0]->weight;
$postage_price      =   $order->total - $parcelArray[0]->original_price;
$options            =   unserialize($Quote->get_shopping_option('shopping_options'));
$chosen_package     =   $options[$package];
$plugin_name        =   $chosen_package['name'];
$price              =   $order->total*$discount/100;
$final_price        =   $order->total - $price;
$plugin_price       =   $final_price * 100;
//$plugin_price       =   $order->total* 100;
$extra_package      =   $order->extra_packages;

//Check if user is new or old
$sql = "SELECT * FROM ems_shopping_purchases WHERE paid = 1 AND user_id = '$order->ems_user_id'";
$row = $wpdb->get_row($sql);

if($row > 0){
    $buyer =    'old';
}else{
    $buyer =    'new';
}

$codeMsg        =   '';
$discount       =   0;
$discountPrice  =   0;
if(isset($_POST['apply_code'])){
    
    $codesArray =   array();
    $rowCode    =   $wpdb->get_results( "SELECT * FROM ems_promocode");
    foreach($rowCode as $value){
        $codesArray[]   =   $value->code;
    }
    
    $appliedStatus  =    1;
    $code           =   $_REQUEST['promo_code'];
    if(in_array($code, $codesArray)){
        
        $sql1 = "SELECT * FROM ems_promocode WHERE code = '$code'";
        $row1 = $wpdb->get_row($sql1);
        $code_discount  =   $row1->discount;
        
        $discount       =   ($order->total * $code_discount) / 100;
        $discountPrice  =   $order->total - $discount;
        $numberFormat   =   number_format($discountPrice, 2, '.', '');
        $plugin_price   =   $numberFormat * 100;
        $codeMsg        =   'Code applied successfully. You will get '.$code_discount.'% discount';
    }else{
        $appliedStatus  =    0;
        $codeMsg        =   'Promo Code is not Valid. Please fill correct code and apply.';
    }
}

?>

<div class="wrapper" id="full-width-page-wrapper">
    <div class="<?php echo esc_html($container); ?>" id="content">
        <div class="row">
            <div class="col-md-12 content-area" id="primary">
                <section class="tab-pane active" id="orderStep1">
                    <!-- START STEP 1 -->
                    <div class="lightgreytextboxlp">
                        <strong><?php echo $codeMsg; ?></strong>
                        <?php                        
                        $package_name       =   $_POST['package_name']; 
                        $package_price      =   $_POST['package_price'];
                        $package_currency   =   $_POST['package_currency'];

                        $secret = SECRET_KEY;
                        \Stripe\Stripe::setApiKey( $secret );

                        if(isset($_POST['stripeToken'])){
                            try {
                                if ( !isset($_POST['stripeToken']) )
                                    throw new Exception('The Stripe Token is not correct');

                                /* make a charge */
                                \Stripe\Charge::create( array( 'amount'    => $package_price, 
                                                            'currency'    => $package_currency,
                                                            'source'      => $_POST['stripeToken'],
                                                            'description' => 'Package:  (Name ' . $package_name . ')'
                                                          ) 
                                                  );
                
                                /* if successful - send a plugin by email */                                
                                $where  =   array('ems_session' => $ems_session);
                                
                                $sql2       =   "SELECT * FROM ems_shipments WHERE ems_session = '$ems_session'";
                                $row2       =   $wpdb->get_row($sql2);
                                $arrary     =   json_decode($row2->parcels);
                                foreach($arrary as $value) {
                                    $value->price = $package_price/100;
                                }
                                
                                $data   =   array('is_paid' =>  1,
                                                  'parcels' =>  json_encode($arrary));
                                $wpdb->update('ems_shipments', $data, $where);
                                
                                $data1  =   array('paid'            =>  1,
                                                  'extra_packages'  =>  $_POST['postage_extra'],
                                                  'product_title'   =>  $package_name,
                                                  'amount'          =>  $package_price/100);
                                $where1 =   array('ems_session'     =>  $ems_session);
                                $wpdb->update('ems_shopping_purchases', $data1, $where1);
                                
                                $Quote->purchase_orders($ems_session);

                                echo '<strong>Payment is successfull.<br>You will be redirecting.....please wait</strong>';
                                ?>
                                <script type="text/javascript">
                                    setTimeout(function(){
                                        window.location = "<?php echo get_site_url(); ?>/thank-you/?amount=<?php echo $package_price; ?>&package=<?php echo $package_name; ?>";
                                    }, 3000);
                                </script>
                            <?php
                            } catch (Exception $e) {
                                echo $e->getMessage();
                            }
                        }
                        ?>
                        <h1>Payment</h1><br>
                        <!--
                        <p>You will now be forwarded to WorldPay to pay for your parcels. Once you have paid you will receive an e-mail with your labels and shipment confirmation. If you are not forwarded for payment click the button below....</p>
                        -->
                        <p>Click button to pay for your parcels. Once you have paid you will receive an e-mail with your labels and shipment confirmation. If you are not forwarded for payment click the button below....</p>
                    </div>
                </section>
                <main class="site-main" id="main" role="main">
                    <?php if($buyer == 'new' && $appliedStatus == 0){ ?>
                    <form method="post" action="">
                        <div class="form-group">
                            <label>Promotion Code</label>
                            <input type="text" class="form-control" name="promo_code" required>
                            <input type="submit" value="Apply Code" class="btn btn-primary" name="apply_code"/>
                        </div>                        
                    </form>
                    <?php } ?>
                    <form action="" method="post" class="float-md-right">
                        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-label="Pay With Card"
                            data-key="<?php echo $publishable_key; ?>"
                            data-name="<?php echo $plugin_name; ?>"
                            data-description="Package is: <?php echo $plugin_name; ?>"  
                            data-image="<?php echo get_site_url(); ?>/wp-content/uploads/2017/02/sitelogosmall.jpg" 
                            data-amount="<?php echo $plugin_price; ?>"
                            data-currency="<?php echo $Quote->currency; ?>"
                            data-locale="auto">
                        </script>
                        <?php /* you can pass parameters to php file in hidden fields, for example - plugin ID */ ?>
                        <input type="hidden" name="package_price" value="<?php echo $plugin_price; ?>">
                        <input type="hidden" name="package_name" value="<?php echo $plugin_name; ?>">
                        <input type="hidden" name="package_currency" value="<?php echo $Quote->currency; ?>">
                        <input type="hidden" name="package_dimension" value="<?php echo $dimension; ?>">
                        <input type="hidden" name="package_weight" value="<?php echo $weight; ?>">
                        <input type="hidden" name="postage_price" value="<?php echo $postage_price; ?>">
                        <input type="hidden" name="postage_extra" value="<?php echo $extra_package; ?>">
                    </form> 
                    
                    <!--
                    <form action="https://secure.worldpay.com/wcc/purchase" id='bbg' name="BuyForm" method="POST">
                        <input type="hidden" name="instId"  value="1182871"> The "instId" value "211616" should be replaced with the Merchant's own installation Id  
                        <input type="hidden" name="cartId"  value="<?php echo $ems_session; ?>">
                        <input type="hidden" name="currency" value="<?php echo $Quote->currency; ?>"> Choose appropriate currency that you would like to use 
                        <input type="hidden" name="amount"  value="<?php echo $order->total; ?>">
                         <input type="hidden" name="MC_success" value="http://www.expressmystuff.co.uk/thank-you/"> 
                        <input type="hidden" name="desc" value="EMS Shipment Order: <?php echo $order->collection_address['custName']; ?>">
                         JavaScript is used to give functionality to some of the pages elements. 
                        <br>
                        <br>
                        <input type='submit' class='btn btn-primary btn-lg text-uppercase' value='Buy Now With WorldPay' />
                    </form>
                    -->
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
