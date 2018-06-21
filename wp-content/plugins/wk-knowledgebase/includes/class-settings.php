<?php
    
	if(!class_exists('wk_settings')) {
		/**
		* Construct the plugin setting
		*/
		
		class wk_settings {				
			
            function __construct() {
				add_action('admin_menu',array(&$this,'webkul_knw_admin_menu'));

    			add_action('admin_init',array(&$this,'webkul_knw_reg_function'));

    			add_action('admin_init',array(&$this,'force_article_cat_init'));

				add_action('edit_form_advanced',array(&$this, 'article_force_post_cat'));

                add_action('wp_enqueue_scripts', array(&$this, 'load_recaptcha'));

                add_action( 'knw_settings_page', array( $this, 'knw_settings_page') );

                add_action( 'knw_cat_order_page', array( $this, 'knw_cat_order_page') );

			}

            function load_recaptcha() {
                wp_enqueue_script( 'webkulknw-recaptcha','https://www.google.com/recaptcha/api.js');
            }

            /*
            * This function creates the setting field in admin menu
            */

			function webkul_knw_admin_menu()

    		{

        		add_submenu_page('edit.php?post_type=wk_knowledgebase','Knowledgebase Setting','Settings','administrator','knowledgebase-setting','wk_knw_configuration_page');

    		}

            /*
            * This function registers the setting fields.
            */

    		function webkul_knw_reg_function()

    		{   

        		register_setting('webkul-knw-settings-group','webkul_knw_slug');

        		register_setting('webkul-knw-settings-group','webkul_knw_bnr_top');

                register_setting('webkul-knw-settings-group','webkul_knw_category');

                register_setting('webkul-knw-settings-group','webkul_knw_tag_slug');

        		register_setting('webkul-knw-settings-group','webkul_knw_header_text');

        		register_setting('webkul-knw-settings-group','webkul_knw_bg_color');

                register_setting('webkul-knw-settings-group', 'bnr-url');

                register_setting('webkul-knw-settings-group', 'knw-banner-url');

                register_setting('webkul-knw-settings-group', 'wk-knw-banner');

        		register_setting('webkul-knw-settings-group','webkul_knw_set_article'); 

        		register_setting('webkul-knw-settings-group','webkul_sbt_btn_link_addr');

                register_setting('webkul-knw-settings-group','webkul_captcha_client_key');

                register_setting('webkul-knw-settings-group','webkul_captcha_secret_key'); 
 
    		}

            /*
            * This function shows the statistics of the plugin like total articles,likes and categories.
            */

    		function get_statistics($loves_arr,$count_categories){
    

    			if (!empty($loves_arr)) {
    
        			$sum=array_sum($loves_arr);    
    
    			}
   				else{
        
        			$loves_arr='';
    			}

   				$count_articles = wp_count_posts( 'wk_knowledgebase' )->publish;
				?> 
       
        		<div class="wk-out-wrap col-md-4">
            
            		<div class="wk-statistics wk-likes-count">
                
                		<div class="wk-stats-count">
                        
                    		<span><?php if(!empty($loves_arr)) echo $sum; else echo '0'?></span>
                        
                    		<p>Likes</p>

                		</div>
            
            		</div>        

        		</div>        
     
        
        		<div class="wk-out-wrap col-md-4">
            
           			<div class="wk-statistics wk-article-count">
                
                		<div class="wk-stats-count">
                        
                    		<span><?php if(!empty($count_articles)) echo $count_articles; else echo "0"; ?></span>
                        
                    		<p>Articles</p>

                		</div>
            
            		</div>        

        		</div>         
     
        
        		<div class="wk-out-wrap col-md-4">
            
            		<div class="wk-statistics wk-category-count">
                
                		<div class="wk-stats-count">
                        
                    		<span><?php if(!empty($count_categories)) echo $count_categories; else echo "0"; ?></span>
                        
                    		<p>Categories</p>

                		</div>
            
            		</div>        

        		</div>        
     

				<?php     
			}

            /*
            * This function returns the content excerpt. $limit is the word limit.
            */

			function wk_short_content($limit) {
    			$content = get_the_content();
    			$pad="&hellip;";
    
    			if(strlen($content) <= $limit) {
        			return strip_tags($content);
    			} else {
        			$content = substr($content, 0, $limit) . $pad;
        			return strip_tags($content);
    			}
			}	

            // BreadCrumb Function

            function wk_knw_breadcrumb() {

                $seperator = ' &gt ';
                $home_page = 'Home'; 

                if( is_single() || is_tax() || is_search()) {
                    echo "<ul class='wk-breadcrumb'>";
                            
                    echo '<li"><a href='. get_post_type_archive_link("wk_knowledgebase") .'>' . $home_page . '</a></li>';
                    
                            
                    if (is_tax() ) {
                        echo '<li class="breadcrumb-item"><strong>' . single_cat_title('', false) . '</strong></li>';
                    }

                    elseif (is_single()) {
                        echo '<li class="breadcrumb-item"><strong>'.get_the_title().'</strong></li>';
                    }
                    elseif (is_search()) {
                        echo '<li class="breadcrumb-item"><strong>Search</strong></li>';
                    }

                    echo '<hr>';

                    echo "</ul>";
                }
            }

            /*
                This function creates the request query in case of user doesn't find relevant result in search.
            */

			function submit_your_request(){
    			?>
   				<div class="wk-submit-request text-center" id="modal-query-request">
                
        			<?php 
                	$mailto = (get_option('webkul_sbt_btn_link_addr')) ? get_option('webkul_sbt_btn_link_addr') : '';
              
              		if (!empty($mailto)) : ?>

                        <p>Didn't find relevant content, <a href="#modal-query" class="frm-btn">Submit Your Request</a> to our support team.</p>      
        
        			<?php   endif;

        			?>

    			</div>

				<?php

			}	


            function request_form() {
                ?>
                <div id="blind">
    
                    <div class="container">
                        <div id="modal-query" class="wk-modal">
                            <a href="#" class="close-modal"></a>
                            <h2>Submit Your Queries</h2>
                            <form action="" method="post" id="query-form" class="form-horizontal" role="form">
                                <div class="modal-body">
                                    <div class="form-group">        
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="user_name" name="user-name" placeholder="Name" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group">        
                                        <div class="col-sm-12">
                                            <input type="email" class="form-control" id="user_email" name="user-email" placeholder="Email" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group">        
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="query_subject" name="query-subject" placeholder="Query Subject" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group">        
                                        <div class="col-sm-12">
                                            <textarea class="form-control" rows="6" name="query-message" id="query_message" placeholder="Write your query here..." required></textarea>
                                        </div>
                                    </div>    
                                    <div class="g-recaptcha" id="recaptcha" data-sitekey="<?php echo get_option('webkul_captcha_client_key'); ?>" style="transform:scale(0.77);transform-origin:0;-webkit-transform:scale(0.77);transform:scale(0.77);-webkit-transform-origin:0 0;transform-origin:0 0;"></div> 
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="wait"></div>
                                        <input id="submit" name="query-submit" type="submit" value="Submit" class="btn btn-primary">          
                                    </div>
                                </div>   

                            </form>
                        </div>
                    </div>
            </div>
                <?php
            }

            /*
                This function is for keeping aticle unpublish if any category doesn't assigned to the article.
            */

			function force_article_cat_init() {
  				wp_enqueue_script('jquery');
			}

			function article_force_post_cat() {
    			echo "<script type='text/javascript'>\n";
  				echo "
  				jQuery('#publish').click(function() {
    				var cats = jQuery('[id^=\"taxonomy\"]')
      				.find('.selectit')
      				.find('input');
  					if(cats.length) {
    					category_selected=false;
    					for (counter=0; counter<cats.length; counter++) {
      						if (cats.get(counter).checked==true) {
        						category_selected=true;
        						break;
      						}
    					}
    					if(category_selected==false) {
      						alert('You have not selected any category for the Article. Kindly select Article category.');
      						setTimeout(\"jQuery('#ajax-loading').css('visibility', 'hidden');\", 100);
      						jQuery('[id^=\"taxonomy\"]').find('.tabs-panel').css('background', '#F96');
      						setTimeout(\"jQuery('#publish').removeClass('button-primary-disabled');\", 100);
      						return false;
    					}
  					}
  				});
  				";
   				echo "</script>\n";
			}
            

            /*
                This function shows the list from where article can be shared or liked/unliked.
            */
            function list_helpful() { 
                ?>
                <div class="wk-main-wrapper">
                    <div class="wk-list-help">
                        <div class="wk-help">
                            <div class="wk-text-wrap" id="feeback-confirm">
                                <p>Was this article helpful?</p>
                                <span id="feedback-text"></span>
                                <span class="dec">
                                    <a href="" id="helpful" data-post-id="<?php echo get_the_ID();?>"><span class="yes"></span></a>
                                    <a href="" id="not-helpful"><span class="no"></span></a>
                                </span>
                                    <?php 
                                    if(isset($_POST['wk-submit']) && empty($_POST['wk_feedback'])) {
                                        echo '<script>';
                                        ?>
                                            jQuery(".wk-help a").remove();
                                            jQuery("#feedback-text").text("Please fill the field!");
                                        <?php
                                        echo '</script>';
                                    }
                                    else if(isset($_POST['wk-submit']) && !empty($_POST['wk_feedback'])){

                                        if(isset($_POST['g-recaptcha-response'])){ 
                                            $captcha=$_POST['g-recaptcha-response'];
                                        }
                                        if(!$captcha){
                                            echo '<script>';
                                            ?>
                                                jQuery(".wk-help a").remove();
                                                jQuery("#feedback-text").text("Please Check The Captcha Form!");
                                            <?php
                                            echo '</script>';
                                        }
                                        else {
                                            $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?<?php echo get_option('webkul_captcha_secret_key');?>&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
                                            if($response.success==false){
                                                echo '<script>';
                                                ?>
                                                    jQuery(".wk-help a").remove();
                                                    jQuery("#feedback-text").text("You are Spammer!!!");
                                                <?php
                                                echo '</script>';
                                            }
                                            else {
                                                $knw_sbmt_add = (get_option('webkul_sbt_btn_link_addr')) ? get_option('webkul_sbt_btn_link_addr') : '';
                                                if (!empty($knw_sbmt_add)) {
                
                                                    $to=get_option('webkul_sbt_btn_link_addr'); 
                                                
                                                    $subject='Knowledgebase Article Suggestion For'." ".get_the_title();     
                                                    
                                                    $feedback=esc_textarea($_POST['wk_feedback']);
                    
                                                    $confirm=wp_mail($to, $subject, $feedback);
                                                    
                                                    if($confirm){
                                                        $true= add_post_meta(get_the_ID(),'wk_feedback',$feedback);
                                                        if($true) {                            
                                                            echo '<script>';
                                                            ?>
                                                                jQuery(".wk-help a").remove();
                                                                jQuery("#feedback-text").text("Thanks for your feedback!");
                                                            <?php
                                                            echo '</script>';
                                                        }
                                                        else {                            
                                                            echo '<script>';
                                                            ?>
                                                                jQuery(".wk-help a").remove();
                                                                jQuery("#feedback-text").text("Please Resend your feedback!");
                                                            <?php
                                                            echo '</script>';
                                                        }                     
                                                    }
                                                    else {                            
                                                        echo '<script>';
                                                        ?>
                                                            jQuery(".wk-help a").remove();
                                                            jQuery("#feedback-text").text("Please Resend your feedback!");
                                                        <?php
                                                        echo '</script>';
                                                    }
                                                }
                                            }            
                                            
                                        }                                                
                                    }
                                ?>

                            </div>

                            <span class="wk-feedback">
                                <form method="post" action="<?php echo get_permalink()?>#feedback-confirm">
                                    <div>
                                        <textarea type="text" name="wk_feedback" id="wk-feedback" rows="3" placeholder="Please write your reviews here." required></textarea>
                                        <div class="g-recaptcha" id="recaptcha" data-sitekey="<?php echo get_option('webkul_captcha_client_key'); ?>" style="transform:scale(0.77);transform-origin:0;-webkit-transform:scale(0.77);transform:scale(0.77);-webkit-transform-origin:0 0;transform-origin:0 0;"></div>
                                        <input type="submit" name="wk-submit" class="wk-btn-sm">
                                    </div>
                                </form>
                            </span>

                        </div>
                
                            
                        <div class="wk-like">
                        
                            <?php
                            $total_votes=get_post_meta(get_the_ID(), "knw_votes_count", true);

                            if(!empty($total_votes) && $total_votes>1) : ?>    
                                <span class="post-loves"><?php echo $total_votes; ?> Likes</span>
                            <?php elseif(!empty($total_votes)): ?>
                                <span class="post-loves"><?php echo $total_votes; ?> Like</span>
                                                  
                            <?php else: ?>
                                <span class="post-loves">0 Like</span>
                            <?php endif; ?> 
                        
                        </div>
                        </div>
                    </div>
                </div>
            <?php 
            
            }

            function knw_settings_page() {

                $checked="checked='checked'";

                $selected="selected='selected'";

                ?>
     
                <div id="knw-settings" class="wrap">

                <!-- <div class="head">Display settings</div> -->
                    <h1>Display Settings</h1>
                    
                    <form method="post" action="options.php">

                    <!-- ==================== -->

                    <?php settings_fields('webkul-knw-settings-group');?>

                    <table class="form-table">
            
                        <tbody>            
                            
                            <tr>
                                <th scope="row">
                                    <label for="webkul_knw_top">Banner Top Margin</label>
                                </th>
                                <td>
                                        <input id="webkul_knw_top" type="text" name="webkul_knw_bnr_top" class="regular-text" value="<?php echo get_option('webkul_knw_bnr_top');?>"/>
                                        <p class="description">
                                            <?php _e( 'If position of header is Fixed.'); ?>
                                        </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                        <label for="webkul_knw_header">Knowledgebase Header</label>
                                </th>
                                <td>
                                    <input id="webkul_knw_header" class="regular-text" type="text" name="webkul_knw_header_text"  value="<?php echo get_option('webkul_knw_header_text');?>"/>
                                    <p class="description">
                                        <?php _e( 'Text displayed above search field.'); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                        <label for="webkul_knw_slug">Knowledgebase Slug</label>
                                </th>
                                <td>
                                    <input id="webkul_knw_slug" class="regular-text" type="text" name="webkul_knw_slug"  value="<?php echo get_option('webkul_knw_slug');?>"/>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="webkul_knw_category">Knowledgebase Taxanomy Slug</label>
                                </th>
                                <td>
                                    <input id="webkul_knw_category" type="text" name="webkul_knw_category" class="regular-text" value="<?php echo get_option('webkul_knw_category');?>"/> 
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="webkul_knw_tag">Knowledgebase Tag Slug</label>
                                </th>
                                <td>
                                    <input id="webkul_knw_tag" type="text" name="webkul_knw_tag_slug" class="regular-text" value="<?php echo get_option('webkul_knw_tag_slug');?>"/> 
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label>Knowledgebase Banner</label>
                                </th>
                                <td id="front-static-pages">
                                    <fieldset>
                                        <p>
                                        <label>
                                            <input type="radio" name="wk-knw-banner" id="banner-bg-color" value="1" <?php checked(1, get_option('wk-knw-banner'), true); ?>>Plain Background Color
                                        </label>
                                        </p>
                                        <ul style="margin-top: 0">
                                            <li>
                                                <input type="Color" id="sel_webkul_knw_bg_color" name="webkul_knw_bg_clr" value="<?php echo get_option('webkul_knw_bg_color');?>"/>
                                            </li>
                                            <li>
                                              <input id="webkul_knw_bg_color" type="text" name="webkul_knw_bg_color" class="regular-text" value="<?php echo get_option('webkul_knw_bg_color');?>"/> 
                                            </li>
                                        </ul>
                                            <p>
                                            <label>
                                                <input type="radio" name="wk-knw-banner" id="upload-banner" value="2" <?php checked(2, get_option('wk-knw-banner'), true); ?>>Upload Banner
                                            </label>
                                            </p>
                                        <ul style="margin-top: 0" id="bnr-wrap">
                                            <li>
                                                <img src="<?php $up = wp_upload_dir(); $knw_bnr_url = (get_option('knw-banner-url')) ? get_option('knw-banner-url') : ''; if(!empty($knw_bnr_url)) echo $up['baseurl'].get_option('knw-banner-url'); else echo WP_KNOWLEDGEBASE.'assets/images/default-banner-mobile.jpg'; ?>" alt="Upload Banner" id='banner_img' style="width:25em; height:115px">
                                            </li>
                                            <li>
                                                <input type="text" id="banner-upload" class="regular-text" name="bnr-url" placeholder="Banner Url" value="<?php echo get_option('bnr-url'); ?>">
                                                <input type="hidden" name="knw-banner-url" value="<?php echo get_option('knw-banner-url'); ?>" class="knw_banner_url">
                                            </li>
                                            <li id="bnr-wrap">
                                                <input type="button" class="upload_bg_banner button button-primary" value="Upload Banner">
                                            </li>
                                            <li>
                                                <p class="description">
                                                    <?php _e( 'Upload Search Box Background Banner.'); ?>
                                                </p>
                                            </li>
                                        </ul>
                                        <p>
                                        <label>
                                            <input type="radio" name="wk-knw-banner" id="default-banner" value="3" <?php checked(3, get_option('wk-knw-banner'), true); ?>>Default Banner
                                        </label>
                                        </p>
                                    </fieldset>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row">
                                    <label for="webkul_sbt_btn_link_addr">E-mail Address</label>
                                </th>
                                <td>
                                    <input id="webkul_sbt_btn_link_addr" class="regular-text" type="text" name="webkul_sbt_btn_link_addr"  value="<?php echo get_option('webkul_sbt_btn_link_addr');?>"/> 
                                    <p class="description">
                                        <?php _e( 'For Query Mail.'); ?>
                                    </p>
                                </td>
                            </tr> 
                            <tr>
                                <th scope="row">
                                    <label for="webkul_knw_set_article">Number of Articles</label>
                                </th>
                                <td>
                                    <input id="webkul_knw_set_article" type="number" name="webkul_knw_set_article" class="regular-text" value="<?php echo get_option('webkul_knw_set_article');?>"/>
                                    <p class="description">
                                        <?php _e( 'On Knowledgebase landing page.'); ?>
                                    </p> 
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="webkul_captcha_client_key">Re-Captcha Client Key</label>
                                </th>
                                <td>
                                    <input id="webkul_captcha_client_key" type="text" name="webkul_captcha_client_key" class="regular-text" value="<?php echo get_option('webkul_captcha_client_key');?>"/> 
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="webkul_captcha_secret_key">Re-Captcha Secret Key</label>
                                </th>
                                <td>
                                    <input id="webkul_captcha_secret_key" type="text" name="webkul_captcha_secret_key" class="regular-text" value="<?php echo get_option('webkul_captcha_secret_key');?>"/> 
                                </td>
                            </tr>
                     
                        </tbody>
            
                    </table>

                    <!-- ==================== -->
            
                    <?php  

                        submit_button();

                    ?>

                </form>

            </div>

            <?php   

            }		

            function knw_cat_order_page() {
                
                $wk_cat_args = array(
                    'orderby'   => 'meta_value_num',
                    'meta_query' => array(
                       'relation' => 'OR',
                        array( 
                            'key' => 'wk-knw-cat-order',
                            'compare' => '',
                        ),
                        array( 
                            'key' => 'wk-knw-cat-order',
                            'compare' => 'NOT EXISTS'
                        )
                    ),
                    'hide_empty'=> false,
                    'parent'    => 0
                );

                $wk_terms = get_terms(WK_POST_TAXONOMY, $wk_cat_args);

                echo '<p style="margin-top:15px;" class="description">Drag and Drop Categories to arrange order on front-end.</p>';

                echo '<form action="" class="wk-cat-order-form">';
                
                echo '<ul id="wk_knw_categories_list">';
                    
                    foreach ($wk_terms as $key => $value) {
                        
                        echo '<li id="order_id_'.$value->term_id.'" data-term-id="'.$value->term_id.'" class="wk-cat-order">'.$value->name.'</li>';
                                              
                    }

                echo '</ul>';

                submit_button();

                echo '</form>';

            }

        }

        /*
            This function creates the fields in settings api
        */

		function wk_knw_configuration_page() {

            ?>

            <div class="wrap group-form">
                
                <nav class="nav-tab-wrapper">

                <?php

                    $knw_tabs = array(

                        'settings' =>  __('Settings'),
                        'cat_order' =>  __('Arrange Categories')

                    );

                    $current_tab = empty( $_GET['tab'] ) ? 'settings' : sanitize_title( $_GET['tab'] );

                    foreach ( $knw_tabs as $name => $label ) {
                    
                        echo '<a href="' . admin_url( 'admin.php?page=knowledgebase-setting&tab=' . $name ) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
                    
                    }

                ?>

                </nav>

                <h1 class="screen-reader-text"><?php echo esc_html( $knw_tabs[ $current_tab ] ); ?></h1>

                <?php

                    do_action( 'knw_' . $current_tab . '_page' );

                ?>  

            </div>

            <?php

        }

        		
	}	
?>