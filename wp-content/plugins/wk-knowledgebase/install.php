<?php
	if (!class_exists('wk_knw_install')) {
		/**
		* activation function class
		*/
		class wk_knw_install
		{
			function webkul_knw_install(){
 
    			register_setting('webkul-knw-settings-group','webkul_knw_slug');

    			register_setting('webkul-knw-settings-group','webkul_knw_bnr_top');

    			register_setting('webkul-knw-settings-group','webkul_knw_category');

                register_setting('webkul-knw-settings-group','webkul_captcha_client_key');

                register_setting('webkul-knw-settings-group','webkul_captcha_secret_key');

                add_option( 'webkul_captcha_client_key', '6Lc0CQkTAAAAAMegZGcdm0r5D1MqL9XW3DkE6EtB','', 'yes' ); 

                add_option( 'webkul_captcha_secret_key', '6Lc0CQkTAAAAABWII7yVeCX1qYNWrUFnHol_zYES','', 'yes' );

    			add_option( 'webkul_knw_slug', 'knowledgebase','', 'yes' );  

    			add_option( 'webkul_knw_bnr_top', '0px','', 'yes' );  
    
                add_option( 'webkul_knw_category', 'knowledgebase_category','', 'yes' );

                add_option( 'webkul_knw_tag_slug', 'knowledgebase_tag','', 'yes' ); 
                
    			add_option( 'webkul_knw_header_text', 'How Can I Help You?','', 'yes' ); 

    			add_option( 'webkul_knw_bg_color', '#337AB7','', 'yes' ); 

    			add_option( 'webkul_knw_set_article', '5','', 'yes' );

    			add_option( 'webkul_sbt_btn_link_addr', 'support@example.com','', 'yes' );
			}
		}
	}
?>