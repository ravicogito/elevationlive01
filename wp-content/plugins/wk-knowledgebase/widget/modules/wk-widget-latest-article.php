<?php
/*===============
    wk Articles Widget
 ===============*/
 
//========= Custom Knowledgebase Article Widget
add_action( 'widgets_init', 'wk_article_latest_widgets' );
function wk_article_latest_widgets() {
    register_widget( 'wk_Article_Latest_Widget' );
}

//========= Custom Knowledgebase Article Widget Body
class wk_Article_Latest_Widget extends WP_Widget {
    
    //=======> Widget setup
    function __construct() {
        parent::__construct(
            'wk_article_latest_widgets', // Base ID
            __( 'Knowledgebase Latest Article', 'wk' ), // Name
            array( 'description' => __('WP Knowledgebase Latest article widget to show articles on the site', 'wk'), 
                    'classname' => 'wk' ), // Args
            array( 'width' => 300, 'height' => 300, 'id_base' => 'wk_article_latest_widgets' )
        );
    }
    
    //=======> How to display the widget on the screen.
    function widget($args, $widgetData) {
        extract($args);
        
        //=======> Our variables from the widget settings.
        $wk_widget_article_title = $widgetData['txtwkArticleHeading'];
        $wk_widget_article_count = $widgetData['txtwkArticleCount'];
        $wk_widget_article_order = $widgetData['txtwkArticleOrder'];
        $wk_widget_article_orderby = $widgetData['txtwkArticleOrderBy'];
        
        //=======> widget body
        echo $before_widget;
        echo '<div class="wk_widget wk_widget_article">';
        
                if($wk_widget_article_title){
                    echo '<h2>'.$wk_widget_article_title.'</h2>';
                }
                
                if($wk_widget_article_orderby == 'popularity'){
                    $wk_widget_article_args = array( 
                        'posts_per_page' => $wk_widget_article_count, 
                        'post_type'  => 'wk_knowledgebase',
                        'orderby' => 'meta_value_num',
                        'order'	=>	$wk_widget_article_order,
                        'meta_key' => 'wk_post_views_count'
                    );
                }
                else{
                    $wk_widget_article_args = array(
                        'post_type' => 'wk_knowledgebase',
                        'posts_per_page' => $wk_widget_article_count,
                        'order' => $wk_widget_article_order,
                        'orderby' => $wk_widget_article_orderby
                   );
                }
                
                $wk_widget_articles = new WP_Query($wk_widget_article_args);
                if($wk_widget_articles->have_posts()) :
            ?>
                <ul>
            <?php
                    while($wk_widget_articles->have_posts()) :
                        $wk_widget_articles->the_post();
            ?>
                        <li>
                            <span></span>
                            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
                                <?php the_title() ?>
                            </a>
                        </li>
            <?php
                    endwhile;
            ?>
                </ul>
            <?php
                endif;
                
                wp_reset_query();
                
        echo "</div>";
        echo $after_widget;
    }
    
    //Update the widget 
    function update($new_widgetData, $old_widgetData) {
        $widgetData = $old_widgetData;
		
        //Strip tags from title and name to remove HTML 
        $widgetData['txtwkArticleHeading'] = $new_widgetData['txtwkArticleHeading'];
        $widgetData['txtwkArticleCount'] = $new_widgetData['txtwkArticleCount'];
        $widgetData['txtwkArticleOrder'] = $new_widgetData['txtwkArticleOrder'];
        $widgetData['txtwkArticleOrderBy'] = $new_widgetData['txtwkArticleOrderBy'];
		
        return $widgetData;
    }
    
    function form($widgetData) {
        //Set up some default widget settings.
        $widgetData = wp_parse_args((array) $widgetData);
?>
        <p>
            <label for="<?php echo $this->get_field_id('txtwkArticleHeading'); ?>"><?php _e('Article Title:','wk'); ?></label>
            <input id="<?php echo $this->get_field_id('txtwkArticleHeading'); ?>" name="<?php echo $this->get_field_name('txtwkArticleHeading'); ?>" value="<?php echo $widgetData['txtwkArticleHeading']; ?>" style="width:275px;" />
        </p>    
        <p>
            <label for="<?php echo $this->get_field_id('txtwkArticleCount'); ?>"><?php _e('Articles Quantity:','wk') ?></label>
            <input id="<?php echo $this->get_field_id('txtwkArticleCount'); ?>" name="<?php echo $this->get_field_name('txtwkArticleCount'); ?>" value="<?php echo $widgetData['txtwkArticleCount']; ?>" style="width:275px;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('txtwkArticleOrder'); ?>"><?php _e('Articles Order:','wk') ?></label>
            <select id="<?php echo $this->get_field_id('txtwkArticleOrder'); ?>" name="<?php echo $this->get_field_name('txtwkArticleOrder'); ?>">
                <option <?php selected($widgetData['txtwkArticleOrder'], 'ASC') ?> value="ASC"><?php _e('ASC','wk'); ?></option>
                <option <?php selected($widgetData['txtwkArticleOrder'], 'DESC') ?> value="DESC"><?php _e('DESC','wk'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('txtwkArticleOrderBy'); ?>"><?php _e('Articles Order by:','wk') ?></label>
            <select id="<?php echo $this->get_field_id('txtwkArticleOrderBy'); ?>" name="<?php echo $this->get_field_name('txtwkArticleOrderBy'); ?>">
                <option <?php selected($widgetData['txtwkArticleOrderBy'], 'name') ?> value="name"><?php _e('By Name','wk'); ?></option>
                <option <?php selected($widgetData['txtwkArticleOrderBy'], 'date') ?> value="date"><?php _e('By Date','wk'); ?></option>
                <option <?php selected($widgetData['txtwkArticleOrderBy'], 'rand') ?> value="rand"><?php _e('By Random','wk'); ?></option>
                <option <?php selected($widgetData['txtwkArticleOrderBy'], 'popularity') ?> value="popularity"><?php _e('By Popularity','wk'); ?></option>
                <option <?php selected($widgetData['txtwkArticleOrderBy'], 'comment_count') ?> value="comment_count"><?php _e('By Comments','wk') ?></option>
            </select>
        </p>
<?php
    }
}
?>