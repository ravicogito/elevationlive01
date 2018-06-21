<?php

    /*
        This template shows single article.
    */
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
<section class="container">
    <div class="col-md-12">
        <?php $wk_settings->wk_knw_breadcrumb(); ?>
    </div>
</section>

<section class="wk-knowledgebase" id="wk-content">    

    <!--content-->

    <div id="container" class="container">
         
        <div class="row">

            <div class="col-md-9">            
                <div id="wk_articles_meta" class="wk_single_page">
                        
                    <ul class="wk-article-wrapper">
                           
                        <?php
                        
                        $color_code='';

                            while(have_posts()) :

                                the_post();
                                    
                                $total_votes=get_post_meta(get_the_ID(), "knw_votes_count", true); 

                                $term = get_the_terms( get_the_ID(),WK_POST_TAXONOMY );
                                
                                if(!empty($term)) :

                                    foreach ($term as $terms) {
                                            $t_id=$terms->term_id;
                                            $t_name=$terms->name;
                                            $wk_term_slug=$terms->slug;
                                        } 
                                    $term_meta = get_option( "taxonomy_$t_id" );     

                                    if(!empty($term_meta['custom_term_meta']))
                                        $color_code="style='border-bottom:solid 4px".$term_meta['custom_term_meta']."'";
                                    else
                                        $color_code=''; 

                                    ?>

                                    <li <?php post_class()?>>

                                        <?php //  Never ever delete it !!!
                                                    
                                            $wk_article_type->wk_set_articles_watched(get_the_ID());
                                        ?>

                                        <div class="wk-entry-wrap">                                                                                                      
                                            <p> <?php the_title(); ?> </p>

                                        </div>       

                                        <div class="wk-article-content"> <?php the_content(); ?> </div>

                                        <?php
                                            $wk_settings->list_helpful();
                                            $author = get_the_author();
                                            $ltr = substr($author, 0, 1);
                                        ?>

                                        <div class="wk-article-author">
                                            <span class="wk-author-img">
                                               <!-- <i></i> -->
                                               <p> <?php echo $ltr; ?> </p> 
                                            </span>
                                            <span class="wk-article-meta">
                                                <span class="wk-author-detail">Author -</span><span class="wk-author-name"><?php echo get_the_author(); ?></span>
                                                <br>
                                                <span class="wk-author-detail">Published on - </span><span class="wk-author-name"> <?php echo get_the_date('F j, Y G:i'); ?></span> 
                                            </span>
                                            
                                            <div class="wk-tags-list">
                                            
                                            <?php  

                                            $post_tags = get_the_terms(get_the_ID(), 'wk_tags');
                                            
                                            if ($post_tags) {

                                                $arr_tag=array();

                                                echo '<span>Tags - </span>';

                                                foreach($post_tags as $tag) {

                                                  $arr_tag[]="<a href=".get_term_link($tag->slug, 'wk_tags').">".$tag->name."</a>";

                                              }
                                   
                                                echo implode(' ',$arr_tag); 

                                            }
                                             else{ ?>

                                                <span class="no-tag-found">No Tags Found</span> 


                                            <?php

                                            }  ?>
                                            </div>
                                        </div>
                                                    
                                    </li>   
                                    <hr class="btm-line">

                                <?php
                                endif;

                            endwhile;
 
                            $wk_article_type->wk_get_articles_watched(get_the_ID());
                        ?>                        
                    </ul>    
                    
                </div> 
                <div class="col-md-3">
                    <div class="wk-trend-sidebar">
                        <?php dynamic_sidebar('wk_sidebar'); ?>
                    </div>
                </div>   
                </div>           

            <div class="row">                
                    <div class="col-md-9">
                        <div class="wk_reply">
                                        
                            <?php
                                            
                                comments_template();
                                        
                            ?>
                                        
                        </div> 
                    </div>
                    <div class="col-md-3"></div>
                </div>            
            </div>
        </div>
    </section>     
</div>
<?php get_footer(); ?>