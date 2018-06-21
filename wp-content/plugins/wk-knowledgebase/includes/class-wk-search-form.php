<?php
	if(!class_exists('wk_search_knw')){
    /*
    * Serch form function class
    */
    class wk_search_knw{
        function wk_search_form(){
        ?>
        <!-- #live-search -->
        <div id="live-search">
            <p class="text-center"><?php echo get_option('webkul_knw_header_text'); ?></p>
                <form role="search" method="get" id="searchform" class="clearfix" action="<?php echo home_url( '/' ); ?>" autocomplete="off">

                    <div class="wk-form-element wk-btn">
                        <button type="submit">
                            <i class="wk-knowledge-search"></i>
                        </button>
                    </div>

                    <div class="wk-form-element wk-textfield">
                        <input type="text"  name="s" id="s" placeholder="Search anything related to our site"/>
                    </div>
                    
                    <input type="hidden" name="post_type" value="wk_knowledgebase" />
                </form>
        </div>
        <!-- /#live-search -->
        <?php
        }
    }
}
?>