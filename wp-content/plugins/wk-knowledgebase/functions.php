<?php
/*
* Plugin Name: Webkul Knowledgebase
* Plugin URI: http://wordpressdemo.webkul.com/knowledgebase/help
* Description: Knowledgebase Plugin for Creating Articles
* Author: Webkul Plugins
* Version: 1.0
* Author URI: http://webkul.com
* Domain Path: plugins/wk-knowledgebase
* Network: true
* License: GNU/GPL for more info see license.txt included with plugin
* License URI: http://www.gnu.org/licenseses/gpl-2.0.html
*/

 //=========> Define plugin path

define( 'WP_KNOWLEDGEBASE', plugin_dir_url(__FILE__));
 
define( 'WK_PLUGIN_VERSION', '1.0' );

define('WK_POST_TAXONOMY', 'wk_taxonomy');

define('WK_PAGE_TITLE', 'knowledgebase');

if (!class_exists('wk_plugin')) {
    /**
    * plugin class
    */
    class wk_plugin
    {
        
        function __construct()
        {
            require_once(sprintf("%s/includes/class-settings.php", dirname(__FILE__)));  

            require_once(sprintf("%s/install.php", dirname(__FILE__)));

            require_once(sprintf("%s/uninstall.php", dirname(__FILE__))); 

            //  include File wk_articles.php

            require_once(sprintf("%s/includes/class-wk-knw-articles.php", dirname(__FILE__)));


            //  include Articles Widget file

            require_once(sprintf("%s/widget/modules/wk-widget-latest-article.php", dirname(__FILE__)));

            require_once(sprintf("%s/widget/modules/wk-widget-popular-article.php", dirname(__FILE__)));

            require_once(sprintf("%s/widget/modules/wk_widget_trending.php", dirname(__FILE__)));

            //=========> wk Search Form
            require_once(sprintf("%s/includes/class-wk-search-form.php", dirname(__FILE__)));

            add_action( 'wp_head', array(&$this, 'knw_fr_style' ));

            add_action('wp_ajax_nopriv_wk_knowledgebase_like', array(&$this, 'wk_knowledgebase_like'));

            add_action('wp_ajax_wk_knowledgebase_like', array(&$this, 'wk_knowledgebase_like'));

            add_action('wp_footer', array(&$this, 'wk_add_live_search'));

            add_action('admin_enqueue_scripts',array($this,'load_backend_script'));

            add_filter('template_include', array(&$this,'wk_choose_template'));

            add_filter('template_include', array(&$this, 'template_chooser'));

            add_action('wp_ajax_nopriv_submit_query_form', array(&$this, 'submit_query_form'));

            add_action('wp_ajax_submit_query_form', array(&$this, 'submit_query_form'));

            add_action('wp_ajax_nopriv_wk_update_category_order', array(&$this, 'wk_update_category_order'));

            add_action('wp_ajax_wk_update_category_order', array(&$this, 'wk_update_category_order'));

            //=========> wk query backend
            require_once(sprintf("%s/includes/class-wk-query.php", dirname(__FILE__)));

        }

        /*
        * Editable style from backend
        */

        function knw_fr_style(){
        ?>

            <style type="text/css" media="screen">
        
            <?php if(get_option('wk-knw-banner')=='3'){ ?>
            .wk-know-banner{   
                background-image: url('<?php echo WP_KNOWLEDGEBASE."assets/images/default-banner1.png"; ?>');
                padding: 85px 0px 96px 0px;
                margin-top: <?php $bnr_top = (get_option('webkul_knw_bnr_top')) ? get_option('webkul_knw_bnr_top') : ''; if(!empty( $bnr_top)) echo  get_option('webkul_knw_bnr_top'); ?>;
                width: 100%;            
            } 
            <?php } 
             else if(get_option('wk-knw-banner')=='2'){ ?>
                .wk-know-banner{   
                    background-image: url("<?php $up = wp_upload_dir(); $knw_bnr_url = (get_option('knw-banner-url')) ? get_option('knw-banner-url') : ''; if(!empty($knw_bnr_url)) echo $up['baseurl'].get_option('knw-banner-url'); ?>");
                    padding: 85px 0px 96px 0px;
                    margin-top: <?php $bnr_top = (get_option('webkul_knw_bnr_top')) ? get_option('webkul_knw_bnr_top') : ''; if(!empty( $bnr_top)) echo  get_option('webkul_knw_bnr_top'); ?>;
                    width: 100%;
                }
            

            <?php } else { ?>
            .wk-know-banner{                   
                background-color: <?php $bnr_bg_color = (get_option('webkul_knw_bg_color')) ? get_option('webkul_knw_bg_color') : ''; if(!empty( $bnr_bg_color )) echo get_option('webkul_knw_bg_color') ?>; 
                padding: 85px 0px 96px 0px;
                margin-top: <?php $bnr_top = (get_option('webkul_knw_bnr_top')) ? get_option('webkul_knw_bnr_top') : ''; if(!empty( $bnr_top )) echo  get_option('webkul_knw_bnr_top'); ?>;
                width: 100%;
            }

            <?php } ?>

            .wk-knowledgebase{
                background-color: #fff;
            }
            
            .wk-submit-request p{
                padding: 19px 30px;
                font-size: 16px;
                margin: 35px 0px;
                color: #fff;
                background-color:#32be79;
                border: none;
                font-weight: bold;
            }
            
            .wk-help p{
                // font-family:serif !important;   
                color: #fff !important;
                display: inline-block;
                font-size: 16px !important;
                margin: 0px 0px 0px 15px;
            }

        </style>
        <?php
        }

        /*
        * This function is for ajax post like. 
        */
        function wk_knowledgebase_like(){
    
            $nonce = $_POST['nonce'];
 
            if ( ! wp_verify_nonce( $nonce, 'knwnonce' ) )
                die ( 'Busted!');
   
            if(isset($_POST['post_like']))
            {
                $post_id = $_POST['post_id'];
  
                $meta_count = get_post_meta($post_id, "knw_votes_count", true);

                update_post_meta($post_id, "knw_votes_count", ++$meta_count);
     
                echo $meta_count;
            }
            else
                echo "already"; 
            exit;
        }


        //=========> Process Query Form

        function submit_query_form() {

            $nonce = $_POST['nonce'];
            $user_name = $_POST['user_name'];
            $user_email = $_POST['user_email'];
            $query_subject = $_POST['query_subject'];
            $query_message = $_POST['query_message'];
            $captcha = $_POST['captcha'];          

            if(! wp_verify_nonce( $nonce, 'knwnonce' ))
                die ( 'Busted!');

            if(empty($captcha)) {
                echo 'captcha-empty';
                exit;
            }
            else {
                $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?<?php echo get_option('webkul_captcha_secret_key');?>&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
            
                if($response.success==false){
                    echo 'captcha-fail';
                    exit;
                }
                else {
                    if(!empty($user_name) && !empty($user_email) && !empty($query_subject) && !empty($query_message)) {
                        global $wpdb;

                        $table_name = $wpdb->prefix.'knw_queries';

                        if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
                            $sql = "CREATE TABLE $table_name (
                                id int(20) NOT NULL AUTO_INCREMENT,
                                user_name varchar(40) NOT NULL,
                                user_email varchar(255) NOT NULL,
                                query_subject varchar(500) NOT NULL,
                                query text NOT NULL,
                                post_status varchar(20) DEFAULT 'publish',
                               PRIMARY KEY (`id`)
                            );";

                            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

                            dbDelta( $sql );
                        }

                        $check_val=$wpdb->insert($table_name, array(
                            'user_name'     =>$user_name,
                            'user_email'    =>$user_email,
                            'query_subject' =>$query_subject,
                            'query'         =>$query_message
                        ));

                        if ($check_val) :

                            $sbmt_link_add = (get_option('webkul_sbt_btn_link_addr')) ? get_option('webkul_sbt_btn_link_addr') : '';
                            
                            if (!empty($sbmt_link_add)) {

                                $to = get_option('webkul_sbt_btn_link_addr');

                                $headers = "From:".$user_name.'<'.$user_email.'>';

                                $subject = $query_subject;

                                $request_message = 'Name : '.$user_name."\n";

                                $request_message .= 'Email Address : '.$user_email."\n";

                                $request_message .= 'Query : '.$query_message;

                                $confirm=wp_mail($to,$subject,$request_message,$headers);

                                if($confirm) {
                                    $to = $user_email;

                                    $headers = "no-reply";

                                    $message = "Message: Voila! Your details has been successfully received and will be processed shortly.";

                                    $response = wp_mail($to, $headers, $message);

                                    echo 'confirm';
                                    exit;
                                }

                            }   
                        
                        endif;
                    }
                    else {
                        echo 'field-empty';
                        exit;                        
                    }
                }
            }
        }

        function wk_update_category_order() {
            $order = $_POST['order_id'];
            foreach ($order as $key => $value) {
                update_term_meta( $value, 'wk-knw-cat-order', $key );
            }
            wp_die();
        }

        //==========> Enqueue backend script

        function load_backend_script() {
            $up = wp_upload_dir();
            wp_enqueue_media();
            $stylesheet = WP_KNOWLEDGEBASE. 'assets/css/style.css';

            wp_register_style ( 'wk_knw_style', $stylesheet, array(), WK_PLUGIN_VERSION );

            wp_enqueue_style('wk_knw_style');
            wp_enqueue_script( 'wk_knowledgebase_admin', WP_KNOWLEDGEBASE. 'assets/js/backend.js', array( 'jquery' ) );
            wp_localize_script('wk_knowledgebase_admin', 'knw_baseurl_var', array('base' => $up['baseurl'], 'url' => admin_url('admin-ajax.php'),'nonce' => wp_create_nonce('knwnonce')
            ));
        }

        //=========> Enqueue wk Style file in header.php
 
        function wk_add_live_search () {
        
            $stylesheet = WP_KNOWLEDGEBASE. 'assets/css/style.css';

            wp_register_style ( 'wk_knw_style', $stylesheet, array(), WK_PLUGIN_VERSION );

            wp_enqueue_style('wk_knw_style');  

            wp_enqueue_script('wk_live_search', WP_KNOWLEDGEBASE.  'assets/js/jquery.livesearch.js', true);  

            wp_enqueue_script('wk_knowledgebase', WP_KNOWLEDGEBASE.  'assets/js/plugin.js', true);

            wp_localize_script('wk_knowledgebase', 'knw_ajax_var', array('url' => admin_url('admin-ajax.php'),'nonce' => wp_create_nonce('knwnonce')
            ));            

            ?>
           
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    var wk = jQuery('#live-search #s').val();
                    jQuery('#live-search #s').liveSearch({url: '<?php echo home_url(); ?>/?ajax=on&post_type=wk_knowledgebase&s='});
                });
            </script>

            <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery('#s').keyup(function() {
                        jQuery('#search-result').slideDown("slow");
                    });
                });
        
                jQuery(document).ready(function(e) {
                    jQuery('body').click(function(){
                        jQuery('#search-result').slideDown("slow",function(){
                            document.body.addEventListener('click', boxCloser, false);
                        });
                    });
            
                    function boxCloser(e){
                        if(e.target.id != 's'){
                            document.body.removeEventListener('click', boxCloser, false);
                            jQuery('#search-result').slideUp("slow");
                        }
                    }
                }); 
            </script>
        <?php 
        }

        /**
        * This function load the template. 
        */
        function wk_choose_template($template){

            $template_path = apply_filters( 'wk_template_path', 'wk-knowledgebase/' );

            $find = array();
            $file = '';

            if ( is_single() && get_post_type() == 'wk_knowledgebase' ) {
                $file   = 'single-wk-knowledgebase.php';
                $find[] = $file;
                $find[] = $template_path . $file;
            } elseif ( is_tax('wk_taxonomy') || is_tax( 'wk_tags') ) {
                $term   = get_queried_object();

                if ( is_tax( 'wk_taxonomy' ) || is_tax( 'wk_tags' ) ) {

                    $file = 'taxonomy-' . $term->taxonomy . '.php';
                } else {
                    $file = 'archive.php';
                }

                $find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
                $find[] = $template_path . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
                $find[] = 'taxonomy-' . $term->taxonomy . '.php';
                $find[] = $template_path . 'taxonomy-' . $term->taxonomy . '.php';
                $find[] = $file;
                $find[] = $template_path . $file;
            } elseif ( is_post_type_archive( 'wk_knowledgebase' ) || is_page( WK_PAGE_TITLE ) ) {
                $file   = 'wk-knowledgebase.php';
                $find[] = $file;
                $find[] = $template_path . $file;
            }

            if ( $file ) {
                $template = locate_template( array_unique( $find ) ) ;
            if ( ! $template ) {
                $template = trailingslashit( dirname(__FILE__) ) . 'template/' . $file;
            }
            }
        return $template;
        }

        /*
        * This function returns the search template.
        */

        function template_chooser($template){
            global $wp_query;
    
            $post_type = get_query_var('post_type');
    
            if( $wp_query->is_search && $post_type == 'wk_knowledgebase' ){        
                return plugin_dir_path(__FILE__)."template/wk-knw-search.php";
                //  redirect to wk_knw_search.php
            }
    
            return $template;   
        }

        /*
        * The function which creates the pagination.
        */

        function knowledgebase_pagination($tot_post) {  
        
            $prev_arrow = 'Older Posts&nbsp;&raquo;';

            $next_arrow = '&laquo;&nbsp;Newer';

            global $wp_query;

            if($tot_post>0)

            {

                $total=$tot_post/get_option('posts_per_page');
            }

            else

            {

                $total = $wp_query->max_num_pages;

            }             
            $big = 9999999999999; // need an unlikely integer
        
            if( $total > 1 )  {

                if( !$current_page = get_query_var('paged') )

                    $current_page = 1;

                if( get_option('permalink_structure') ) {

                    $format = 'page/%#%/';

                } else {

                    $format = '&paged=%#%';

                }

                echo paginate_links(array(

                    'base'          => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),

                    'format'        => $format,

                    'current'       => max( 1, get_query_var('paged') ),

                    'total'         => ceil($total),

                    'mid_size'      => 2,

                    'type'          => 'list',

                    'prev_text'     => $next_arrow,

                    'next_text'     => $prev_arrow,
                ) );
            }
        } 
    }
}

if (class_exists('wk_plugin')) {
    $wk_plugin = new wk_plugin();
    $wk_settings = new wk_settings();
    $wk_article_type = new wk_knw_article();    
}

if(class_exists('wk_knw_install')) {
    register_activation_hook(__FILE__, array('wk_knw_install', 'webkul_knw_install'));
    $wk_install = new wk_knw_install();
}


if (class_exists('wk_knw_uninstall')) {
    register_deactivation_hook(__FILE__, array('wk_knw_uninstall', 'webkul_knw_uninstall'));
    $wk_uninstall = new wk_knw_uninstall();
}


$wk_search_knw = new wk_search_knw();

//=========> Registering wk widget area
register_sidebar(array(
    'name' => __('WP Knowledgebase Sidebar','wk'),
    'id' => 'wk_cat_widget',
    'description' => __('WP Knowledgebase sidebar area','wk'),
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '<h6>',
    'after_title' => '</h6>',
));

//=========> Register widget area
register_sidebar(array(
    'name'          => __('WP Knowledgebase Sidebar 1', 'wk'),
    'id'            => 'wk_sidebar',
    'description'   => __('WP Knowledgebase sidebar area', 'wk'),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '<h6>',
    'after_title'   => '</h6>'
));


?>