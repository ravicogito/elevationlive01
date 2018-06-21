<?php
 
    /*=========
        This template shows the main page for plugin.
    =========*/   
    if ( ! defined( 'ABSPATH' ) ) exit;
    get_header(); 
    
    // load the style and script
    
    wp_enqueue_style ( 'wk_theme_style' );
  
    wp_enqueue_script( 'wk_live_search' );
    
?>
<div class="wk-main-wrapper">
<section class="wk-know-banner">
    
    <div class="container bg-color">
    
        <!--search field-->
        
        <?php 
            $wk_search_knw->wk_search_form();      
        ?>
        
        <!--/search field-->
    
    </div>

</section>  
<?php $counter=0; ?>
<section class="wk-knowledgebase">

    <div id="container" class="container">
        
          
              <!--content-->
          
                    <div id="wk_content">
          
                        <h1><?php echo get_the_title(WK_PAGE_TITLE) ?></h1>

                        <!--leftcol-->
                        <div class="wk_leftcol">
                            <div class="wk_categories">
                        <?php
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
                                'hide_empty'=> true,
                                'parent'    => 0
                            );

                            $wk_terms = get_terms(WK_POST_TAXONOMY, $wk_cat_args);
                            $loves_arr='';
                            $count_categories='';
                            ?>
                            <div class="row">
                            <?php
                            if (!empty($wk_terms)) :
                            
                                    foreach($wk_terms as $wk_taxonomy): 
                                        $count_categories=count($wk_terms);
                                        $wk_term_id = $wk_taxonomy->term_id;
                                        $wk_term_slug = $wk_taxonomy->slug;
                                        $wk_term_name = $wk_taxonomy->name;
                                        $wk_taxonomy_parent_count = $wk_taxonomy->count;  

                                         $term_meta = (get_option( "taxonomy_$wk_term_id" )) ? get_option( "taxonomy_$wk_term_id" ) : '' ;    
                                         if(!empty($term_meta['custom_term_meta']))
                                            $color_code="style='border-bottom:solid 4px".$term_meta['custom_term_meta']."'";
                                          else
                                          $color_code='';

                                        if($counter!=0 && $counter%3==0){
                                            ?>
                                            <div class="col-md-1"></div>
                                            </div>
                                            <div class="row">
                                            <?php
                                        }
                                        ?>
                                        <div class="col-md-4">
                                        <div class="wk_category">                                        
                                            <ul class="wk_article_list">
                                                <h4>
                                                <?php 
                                                    $up=wp_upload_dir();
                                                    $cat_icon = $up['baseurl'].'/'.get_term_meta($wk_term_id,'term_icon', true); 
                                                    $cat_meta = get_term_meta($wk_term_id,'term_icon', true);
                                                    if(!empty($cat_meta))
                                                        $icon = $cat_icon;
                                                    else
                                                        $icon = WP_KNOWLEDGEBASE.'assets/images/default.png';
                                                ?>
                                                <img src="<?php echo $icon; ?>">
                                                <a href="<?php echo get_term_link($wk_term_slug, 'wk_taxonomy') ?>">
                                              
                                                    <?php echo $wk_term_name; ?>
                                              
                                                </a>
                                            
                                                </h4>
                                                <?php

                                                    $knw_art_no = (get_option('webkul_knw_set_article')) ? get_option('webkul_knw_set_article') : '';
                                               
                                                    if (!empty($knw_art_no))
                                                        $knw_no_articles=get_option('webkul_knw_set_article');
                                                    else
                                                        $knw_no_articles='5';
 
                                                    $wk_post_args = array(
                                                                        'post_type' => 'wk_knowledgebase',
                                                                        'posts_per_page' => $knw_no_articles,
                                                                        'orderby' => 'menu_order',
                                                                        'order' => 'ASC',
                                                                        'tax_query' => array(
                                                                            array(
                                                                                'taxonomy' => 'wk_taxonomy',
                                                                                'field' => 'term_id',
                                                                                'terms' => $wk_term_id
                                                                                )
                                                                            )
                                                                        );
                                                    $wk_post_qry = new WP_Query($wk_post_args);
                                                   
                                                    if($wk_post_qry->have_posts()) :
                                                        
                                                        while($wk_post_qry->have_posts()) :
                                                            
                                                            $wk_post_qry->the_post(); 
                                                            
                                                            $loves_arr[]= get_post_meta(get_the_ID(),'knw_votes_count',true); 
                                                            
                                                            ?>
                                                            
                                                            <li>
                                                            
                                                                <a href="<?php the_permalink(); ?>">
                                                            
                                                                    <?php the_title(); ?>
                                                            
                                                                </a>
                                                            
                                                            </li>
                                                        
                                                        <?php

                                                        endwhile;
                                                    
                                                    else :
                                                    
                                                        echo "No posts";
                                                    
                                                    endif; 
                                                ?>
                                                
                                            
                                        
                                        <?php  if($wk_post_qry->found_posts>$knw_no_articles) : ?>
                                            
                                            <li class='view_all'><a href="<?php echo get_term_link($wk_term_slug, 'wk_taxonomy'); ?>">View all <?php echo $wk_post_qry->found_posts; ?> articles</a></li>
                                     
                                    </ul>
                                <?php endif; ?>    
                                
                                </div>
                                </div>
                                <?php $counter++;
                                    endforeach; 

                                    endif;
                                ?>
                                
                            </div>

                        </div>
                        
                        <!--/leftcol-->

                    </div>
                   
                    <!--content-->
            </div>
       
         

        <div class="row">
            
            <?php $wk_settings->get_statistics($loves_arr,$count_categories); ?>

        </div>

        <div class="wk_art_sidebar">
            
            <div class="row">
              
                <div class="col-md-12">
                    
                    <!--aside-->
                    
                    <div class="wk_aside">

                    <?php
                    
                        dynamic_sidebar('wk_cat_widget');
                    
                    ?>
                    
                    </div>
                    
                    <!--/aside-->
              
                </div>    

            </div>
        </div>
        </div>
    
    </div>

</section>
</div>
<?php get_footer(); ?>