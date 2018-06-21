<?php

/*
*   This template shows particular tag articles.
*/

if ( ! defined( 'ABSPATH' ) ) 

	exit;

get_header();    

// load the style and script
    
wp_enqueue_script( 'wk_live_search' );

// Query for Tag

$wk_tag_slug = get_queried_object()->slug;

$wk_tag_name = get_queried_object()->name;

$wk_tag_id = get_queried_object_id();
    
?>

<div class="wk-main-wrapper">
	
	<section class="wk-know-banner">
	    
	    <div class="container bg-color">
	    
	        <!--search field-->
	        
	        <?php 
	            
	        $wk_search_knw->wk_search_form(); 

	        if(get_query_var('paged')) {

	            $paged =  get_query_var('paged');

	        }

	        else if ( get_query_var('page') ) {

	            $paged = get_query_var('page');

	        }

	        else {

	            $paged = 1;

	        }
	                        
	                        
            $wk_post_args = array(
                'post_type' 		=> 'wk_knowledgebase',
                'posts_per_page' 	=> -1,
                'orderby' 			=> 'menu_order',
                'order' 			=> 'ASC',
                'paged' 			=> $paged,
                'tax_query' 		=> array(
			                                array(
			                                    'taxonomy' => 'wk_tags',
			                                    'field' => 'term_id',
			                                    'terms' => $wk_tag_id
			                                )
			                            )
            );

            $wk_post_qry = new WP_Query($wk_post_args);
	        
	        ?>
	        
	        <!--/search field-->
	    
	    </div>

	</section>

	<section class="container">
	    
	    <div class="col-md-12">
	    
	        <?php $wk_settings->wk_knw_breadcrumb(); ?>
	    
	    </div>
	
	</section>


	<section class="wk-knowledgebase" id="wk_content">       

	    <!--content-->
	    <div id="container" class="container">
	       
	        <div class="row">
	        
	            <div class="col-md-9">
	           
	            	<!--<articles>-->
	       
	            	<div id="wk_articles_meta">
	                    
	                    <div class="wk-entry-category">                                
	                                
	                        <?php
	               
	                            $term_meta = get_option( "taxonomy_$wk_tag_id" ); 
	                        ?>
	                                
	                    </div>

	                    <div class="wk-knw-title-cat">
                            <h3><?php echo '# '.$wk_tag_name;?><span> ( <?php echo $wk_post_qry->found_posts; if($wk_post_qry->found_posts>1) echo ' Articles'; else echo ' Article';?> )</span></h3>

                        </div>
	                    
	                    <ul class="wk-cat-article-wrapper">                        
	                    	
	                    	<?php   

	                    	while($wk_post_qry->have_posts()) :
	                        
	                            $wk_post_qry->the_post();
	                            
	                            $total_votes=get_post_meta(get_the_ID(), "knw_votes_count", true);
	      

	                            if(!empty($term_meta['custom_term_meta']))
	                        
	                                $color_code="style='border-bottom:solid 4px".$term_meta['custom_term_meta']."'";
	                        
	                            else
	                        
	                                $color_code=''; 
	                        
	                        	?>                              

	                            <li <?php post_class()?>>

	                            
		                            <a href="<?php the_permalink(); ?>" class="wk-post-meta">
		                            
		                                <?php the_title(); ?>
		                            
		                            </a>
	                        
	                        	</li>
	                    
	                    		<?php
	                    
	                        endwhile;
	                    
	                    	?>
	                    
	                    </ul>
	                    
	                </div> 

	            </div>
	            
	            <div class="col-md-3">
	                
	                <div class="wk-trend-sidebar">
	                
	                    <?php dynamic_sidebar('wk_sidebar'); ?>
	                
	                </div>
	            
	            </div>
	        
	        </div>

	        <div class="row">
	        
	            <div class="col-md-2"></div>
	            
	            <div class="col-md-8">   
	            
	                <?php $wk_settings->submit_your_request()?>        
	            
	            </div>
	            
	            <div class="col-md-2"></div>
	        
	        </div>   
	
	    </div>

	</section>  

</div>   
    
<?php
    get_footer();
?>

<?php $wk_settings->request_form(); ?>