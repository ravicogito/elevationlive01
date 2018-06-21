<?php
/*
============
    Article Post type
============
*/
if (!class_exists('wk_knw_article')){
    
    class wk_knw_article {
    
        function __construct() {

            add_action('init', array(&$this,'wk_articles'));

            add_action( 'init', array(&$this, 'wk_taxonomies'), 0 );

            add_action( 'init', array( $this, 'wk_tag_taxonomies' ), 0 );

            add_action( 'wk_taxonomy_add_form_fields', array(&$this,'wk_taxonomy_add_new_meta_field'), 10, 2 );

            add_action( 'wk_taxonomy_edit_form_fields', array(&$this, 'wk_taxonomy_edit_meta_field'), 10, 2 );

            add_action( 'edited_wk_taxonomy', array(&$this, 'wk_taxonomy_custom_meta'), 10, 2 );

            add_action( 'create_wk_taxonomy', array(&$this, 'wk_taxonomy_custom_meta'), 10, 2 );

            add_filter("manage_edit-wk_knowledgebase_columns", array(&$this, "knw_edit_columns"));

            add_action("manage_posts_custom_column",  array(&$this, "knw_custom_columns"));

            //To keep the count accurate, lets get rid of prefetching
            remove_action( 'wp_head', array(&$this, 'adjacent_posts_rel_link_wp_head', 10, 0));

            add_filter('manage_edit-wk_taxonomy_columns', array(&$this, 'my_column_header'), 10, 1);


            add_filter('manage_wk_taxonomy_custom_column', array(&$this, 'add_wk_taxonomy_column_content'),10,3);

        }        

        function wk_articles() { 
    
            $knw_slug = get_option('webkul_knw_slug');

            if (!empty($knw_slug))
                $knw_slug = get_option('webkul_knw_slug');
            else
                $knw_slug = 'knowledgebase';

            $labels = array(
                'name'                  =>  __('Knowledgebase', 'knw'),
                'singular_name'         =>  __('Knowledgebase', 'knw'),
                'all_items'             =>  __('Articles', 'knw'),
                'add_new'               =>  __('New Article', 'knw'),
                'add_new_item'          =>  __('Add New Article', 'knw'),
                'edit_item'             =>  __('Edit Article', 'knw'),
                'new_item'              =>  __('New Article', 'knw'),
                'view_item'             =>  __('View Articles', 'knw'),
                'search_items'          =>  __('Search Articles', 'knw'),
                'not_found'             =>  __('Nothing found', 'knw'),
                'not_found_in_trash'    =>  __('Nothing found in Trash', 'knw'),
                'parent_item_colon'     =>  ''
            );
    
            $wk_rewrite = array(
                'slug'          =>  $knw_slug,
                'with_front'    =>  true,
                'pages'         =>  false,
                'feeds'         =>  true,
            );
    
            $args = array(
                'labels'                =>  $labels,
                'public'                =>  true,
                'publicly_queryable'    =>  true,
                'show_ui'               =>  true,
                'query_var'             =>  true,
                'menu_icon'             =>  'dashicons-edit',
                'capability_type'       =>  'post',
                'hierarchical'          =>  false,
                'supports'              =>  array('title','editor','thumbnail','comments','tags','revisions','author'),
                'rewrite'               =>  $wk_rewrite,
                'show_in_menu'          =>  true,
                'show_in_nav_menus'     =>  true,
                'show_in_admin_bar'     =>  true,
                'can_export'            =>  true,
                'has_archive'           =>  true,
                'exclude_from_search'   =>  true
            );
 
            register_post_type( 'wk_knowledgebase' , $args );
        }
        

        // Article taxonamy
        function wk_taxonomies() {  
            $knw_category_slug = get_option('webkul_knw_slug');
            // Add new taxonomy, make it hierarchical (like categories)
             if (!empty($knw_category_slug))
                $knw_category_slug=get_option('webkul_knw_category');
            else
                $knw_category_slug='knowledgebase_category';
    
            $labels = array(
                'name'              =>  __( 'Knowledgebase Categories', 'knw'),
                'singular_name'     =>  __( 'Knowledgebase Category', 'knw' ),
                'search_items'      =>  __( 'Search Category', 'knw' ),
                'all_items'         =>  __( 'All Knowledgebase Categories', 'knw' ),
                'parent_item'       =>  __( 'Parent Knowledgebase Category', 'knw' ),
                'parent_item_colon' =>  __( 'Parent Knowledgebase Category:', 'knw' ),
                'edit_item'         =>  __( 'Edit Knowledgebase Category', 'knw' ),
                'update_item'       =>  __( 'Update Knowledgebase Category', 'knw' ),
                'add_new_item'      =>  __( 'Add New Knowledgebase Category', 'knw' ),
                'new_item_name'     =>  __( 'New Knowledgebase Category Name', 'knw' ),
                'menu_name'         =>  __( 'Categories', 'knw' )
            );  
    
            register_taxonomy( 'wk_taxonomy', array( 'wk_knowledgebase' ), array(
                'hierarchical'      =>  true,
                "labels"            =>  $labels,
                "singular_label"    =>  __( 'Knowledgebase Category', 'knw'),
                'show_ui'           =>  true,
                'query_var'         =>  true,
                'rewrite'           =>  array( 'slug' => $knw_category_slug, 'with_front' => true )
            ));
        
        }

        // Article tag

        function wk_tag_taxonomies() {

            $knw_tag_slug = get_option( 'webkul_knw_tag_slug' );
            
            if ( !empty( $knw_tag_slug ) )
            
                $knw_tag_slug = get_option( 'webkul_knw_tag_slug' );
            
            else
                
                $knw_tag_slug = 'knowledgebase_tag';

            $labels = array(
                'name'                  => _x( 'Knowledgebase Tags', 'knw' ),
                'singular_name'         => _x( 'Knowledgebase Tag', 'knw' ),
                'search_items'          => __( 'Search Tags' ),
                'popular_items'         => __( 'Popular Tags' ),
                'all_items'             => __( 'All Knowledgebase Tags' ),
                'parent_item'           => null,
                'parent_item_colon'     => null,
                'edit_item'             => __( 'Edit Knowledgebase Tag' ), 
                'update_item'           => __( 'Update Knowledgebase Tag' ),
                'add_new_item'          => __( 'Add New Knowledgebase Tag' ),
                'new_item_name'         => __( 'New Knowledgebase Tag Name' ),
                'separate_items_with_commas' => __( 'Separate tags with commas' ),
                'add_or_remove_items'   => __( 'Add or remove tags' ),
                'choose_from_most_used' => __( 'Choose from the most used tags' ),
                'menu_name'             => __( 'Tags' ),
             ); 

            register_taxonomy( 'wk_tags', 'wk_knowledgebase', array(
                'hierarchical'  => false,
                'labels'        => $labels,
                'show_ui'       => true,
                'update_count_callback' => '_update_post_term_count',
                'query_var' => true,
                'rewrite'   => array( 'slug' => $knw_tag_slug, 'with_front' => true ),
            ));
        
        }


        // Add New field in Category Page

        // Add term page
        function wk_taxonomy_add_new_meta_field() {
            // this will add the custom meta field to the add new term page
            ?>
            <div class="form-field" id="cat-wrap">
                <label for="cat-image">Category Icon</label>
                   <img src="<?php echo WP_KNOWLEDGEBASE.'assets/images/default1.png'; ?>" alt="Please Update Icon" id='cate_img' style="width:35px; height:35px">
                <br><input type="text" id="cat-image" name="cat-icon" placeholder="Category Icon Url" value="" class="cat_icon_url"><br><br>
                <input type="button" class="upload_cat_icon button button-primary" value="Upload icon">
                <p class="description">
                    <?php _e( 'Assign terms a custom icon to visually separate them from each-other.'); ?>
                </p>
            </div> 
        <?php
        }
        

        // Edit term page
        function wk_taxonomy_edit_meta_field($term) {
    
            // put the term ID into a variable
            $t_id = $term->term_id;
            $up = wp_upload_dir();
            $src = get_term_meta($t_id, 'term_icon', true);
            ?>           

            <tr class="form-field">
                <th scope="row" valign="top">
                    <label for="cat-image">Category Icon</label>
                </th>

                <td id="cat-wrap">
                    <img src="<?php echo $up['baseurl'].'/'.$src; ?>" alt="Please Update Icon" id='cate_img' style="width:35px; height:35px">
                    <br><input type="text" id="cat-image" name="cat-icon" placeholder="Category Icon Url" value="<?php echo $up['baseurl'].'/'.$src; ?>" class="cat_icon_url"><br><br>
                    <input type="button" class="upload_cat_icon button button-primary" value="Upload icon">
                    <p class="description">
                        <?php _e( 'Assign terms a custom icon to visually separate them from each-other.'); ?>
                    </p>
                </td>
            </tr>
        <?php
        }
        
        // Save extra taxonomy fields callback function.
        function wk_taxonomy_custom_meta( $term_id ) {
            
            if ( isset( $_POST['cat-icon'] ) ) {
                $icon_url = $_POST['cat-icon'];
                $up=wp_upload_dir();
                $icon_url=str_replace($up['baseurl'],"",$icon_url);
                $icon_url=substr( $icon_url, 1 );
                update_term_meta($term_id, 'term_icon', $icon_url);
            }
        }  
        

        // Add New field in category page

        function wk_set_articles_watched($postID) {
            $count_key = 'wk_post_views_count';
            $count = get_post_meta($postID, $count_key, true);
    
            if($count==''){
                $count = 1;
                delete_post_meta($postID, $count_key);
                add_post_meta($postID, $count_key, '1');
            }else{
                $count++;
                update_post_meta($postID, $count_key, $count);
            }
        }

                
        function wk_get_articles_watched($postID){
            $count_key = 'wk_post_views_count';
            $count = get_post_meta($postID, $count_key, true);
    
            if($count==''){
                delete_post_meta($postID, $count_key);
                add_post_meta($postID, $count_key, '1');
                return "1 View";
            }
            return $count.' Views';
        }
             
        function knw_edit_columns($columns){
            $columns = array(  
                "cb"        =>  "<input type=\"checkbox\" />", 
                "title"     =>  __("Title", "knw"),
                "author"    =>  __("Author", "knw"),
                "cat"       =>  __("Category", "knw"),
                "views"     =>  __("Views", "knw"),
                ""
            );
            return $columns;  
        }    
  
           
        function knw_custom_columns($column){
            global $post;  
            switch ($column){ 
                case "title":         
                    the_title();
                break; 
                case "author":         
                    the_author();
                break;
                case "cat":         
                    echo get_the_term_list( $post->ID, 'wk_taxonomy' , ' ' , ', ' , '' );
                break;
                case "views":
                    $views = get_post_meta($post->ID, 'wk_post_views_count', true);
                    if($views){
                        echo $views .__(' Views', 'knw');
                    }else{
                        echo __('No Views', 'knw');
                    }
                break; 
            }
        }

        //For adding new fields 
        function my_column_header($columns)
        {
            $new_columns = array(
                "cb"          =>  "<input type=\"checkbox\" />", 
                "name"        =>  __("Name", "knw"),
                "description" =>  __("Description", "knw"),
                "slug"        =>  __("Slug", "knw"),
                "posts"       =>  __("Count", "knw"),
                "header_icon" =>  __("Icon", "knw")
                );            
            return $new_columns;
        }

        function add_wk_taxonomy_column_content($content,$column_name,$term_id) {
            $up = wp_upload_dir();
            $src = get_term_meta($term_id, 'term_icon', true);
            $src = $up['baseurl'].'/'.$src;
            switch ($column_name) {
                case 'header_icon':                
                    $content .= "<img src=$src style='width:30px'";
                    break;                
                default:
                    break;
            }
            return $content;
        }
    }
}
?>