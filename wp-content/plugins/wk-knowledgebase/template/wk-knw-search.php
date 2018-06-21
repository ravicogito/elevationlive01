<?php
    
/*
*   This template shows the search results.
*/
?>

<div class="wk-main-wrapper">

<?php
if(!empty($_GET['ajax']) ? $_GET['ajax'] : null) {
    if ( have_posts() ) {
?>
        <ul id="search-result">
    <?php
        while (have_posts()) : the_post();
    ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            
    <?php
        endwhile;
    ?>
        </ul>

<?php
    } else {
?>
        <span class="wk_no_result">Search result not found......</span>
<?php
    }
} else {
    get_header();
    // load the style and script
    
    wp_enqueue_style ( 'wk_theme_style' );
    
    wp_enqueue_script( 'wk_live_search' );
?>

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


<section class="wk-knowledgebase" id="wk_content">       

    <!--content-->

    <div id="container" class="container">

    <div class="row">
        
        <div class="col-md-9">
            
            <?php
  
                $wk_search_term = $_GET['s'];
                
                 if(get_query_var('paged'))

                {

                    //$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

                    $paged =  get_query_var('paged');

                }

                else if(get_query_var('page'))

                {

                    $paged =  get_query_var('page');

                }

                else

                {

                    $paged=1;

                }

 

                $args = array(
                        'post_type' => 'wk_knowledgebase',
                        'posts_per_page' => get_option( 'posts_per_page' ),
                        'paged' => $paged,
                        's' => $wk_search_term,
                    );
                $the_query = new WP_Query($args);
                if(!empty($wk_search_term))
                    $search_count = $the_query->found_posts;
                else
                    $search_count = 0;
            ?>                
                
 
                <div id="wk_articles_meta">
                    
                    <ul class="wk-search-article-wrapper">
                        <span class="wk-search-wrap"><?php echo $search_count; _e(' Results for  '); ?><h2>"<?php echo $wk_search_term; ?>"</h2>
                        </span>
                    <?php

                        $color_code='';
                        
                        if ($the_query->have_posts() && !empty($wk_search_term)) :
                        
                            while($the_query->have_posts()) :
                                
                                $the_query->the_post();
                               
                                $total_votes=get_post_meta(get_the_ID(),"knw_votes_count", true);
                                
                                $term = get_the_terms( get_the_ID(),WK_POST_TAXONOMY );
                                
                                if(!empty($term)) : 

                                        foreach ($term as $terms) {
                                             $t_id=$terms->term_id;
                                             $t_name=$terms->name;
                                             $wk_cat_slug=$terms->slug;
                                        } 
                                        
                                        $term_meta = get_option( "taxonomy_$t_id" );     

                                        // if(!empty($term_meta['custom_term_meta']))
                                        //     $color_code="style='border-bottom:solid 4px".$term_meta['custom_term_meta']."'";
                                        // else
                                        //     $color_code=''; 
                                    ?>

                                    <li <?php post_class()?> <?php echo $color_code;?>>
                                
                                        <div class="wk-entry-wrap">
              
                                            <!--<div class="wk-knw-title">
                                              
                                                <a href="<?php echo get_term_link($wk_cat_slug, 'wk_taxonomy') ?>">
                                        
                                                    <i><?php echo $t_name;?></i>
                                                
                                                </a>

                                            </div> -->

                                            <a href="<?php the_permalink(); ?>" class="post-meta">
                                        
                                            <?php the_title(); ?>
                                        
                                            </a>

                                            <p><?php echo $wk_settings->wk_short_content(200); ?></p>

                                            <?php if(!empty($total_votes)&&$total_votes>1) : ?>    
                                                <span class="post-loves"><?php echo $total_votes; ?> Likes</span> 
                                            <?php elseif(!empty($total_votes)) : ?>
                                                <span class="post-loves"><?php echo $total_votes; ?> Like</span>
                                            <?php else : ?>
                                                <span class="post-loves"></span>
                                            <?php endif; ?> 

                                              
                                                
                                        </div>
                                        <hr class="btm-line"> 

                                        <!--<div class="wk-ico-wrap" style="background-color:<?php if(!empty($term_meta['custom_term_meta'])) echo $term_meta['custom_term_meta'];?>">
                                        
                                            <span class="wk-doc-ico"></span>
                                        
                                        </div> 
                                        
                                        <a href="<?php the_permalink(); ?>" class="wk-post-meta" style="color:<?php echo $term_meta['custom_term_meta'];?>">
                                        
                                            <?php the_title(); ?>
                                        
                                        </a>

                                        <p><?php echo $wk_settings->wk_short_content(300); ?></p>
                                        
                                        <div class="wk_read_more">
                                         
                                            <a href="<?php the_permalink(); ?>">Read more</a>
                                       
                                        </div> -->
                                    
                                    </li>
                                
                                <?php

                                    endif; 
                            
                            endwhile;

                        elseif(empty($wk_search_term)) :
                            echo "<h3>Please enter any term in search field.</h3>";

                        else : 
                        
                            echo "<p>No relevant articles found. Please try some different keywords</p>";

                        endif;    
                    
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

        <?php if ($the_query->found_posts > get_option('posts_per_page')) : ?>

        <div class="row">
            
            <div class="col-md-1"></div>
            
            <div class="col-md-10">
                
              
                    <div class="wk-pagination-wrapper">

                        <?php $wk_plugin->knowledgebase_pagination($the_query->found_posts);
                    
                        wp_reset_query();?>


                    </div>
            
            </div>

            <div class="col-md-1"></div>
        
        </div>

        <?php     endif;  ?>

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
}
?>
<?php $wk_settings->request_form(); ?>