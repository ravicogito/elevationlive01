<?php
	if (!class_exists('wk_knw_uninstall')) {
		/**
		* deactivation function class
		*/
		class wk_knw_uninstall
		{
			function webkul_knw_uninstall(){

    			// delete CPT posts ##
    
    			global $wpdb;

    			$posts_table = $wpdb->posts;

    			$query = "DELETE FROM {$posts_table} WHERE post_type = 'wk_knowledgebase'";

    			$wpdb->query($query);

    			$taxonomy = 'wk_taxonomy';

    			$terms = get_terms($taxonomy);

    			$count = count($terms);
     
    			if ( $count > 0 ){

        			foreach ( $terms as $term ) {
     
            			wp_delete_term( $term->term_id, $taxonomy );
     
        			}     
    			}
    
    			unregister_setting('webkul-knw-settings-group','webkul_knw_slug');

    			unregister_setting('webkul-knw-settings-group','webkul_knw_bnr_top');

    			unregister_setting('webkul-knw-settings-group','webkul_knw_category'); 

    			unregister_setting('webkul-knw-settings-group','webkul_knw_bg_color');

    			unregister_setting('webkul-knw-settings-group','webkul_knw_set_article');

    			unregister_setting('webkul-knw-settings-group','webkul_sbt_btn_link_addr');

    			delete_option('webkul_knw_bnr_top');
    
    			delete_option('webkul_knw_slug');
    
    			delete_option('webkul_knw_category'); 

    			delete_option('webkul_knw_bg_color');

    			delete_option('webkul_knw_set_article'); 

    			delete_option('webkul_sbt_btn_link_addr'); 
 
			}
		}
	}
?>