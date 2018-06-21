$k=jQuery.noConflict(); 
(function($k){
    $k(document).on('click','.upload_cat_icon',function(event) {
        var parent_id= $k(this).parent().attr('id');
        var custom_uploader;
        event.preventDefault();
            var custom_uploader = wp.media({
          title:'Icon Upload',
                button: {
                    text: 'Upload Icon',
                },
                multiple: false  // Set this to true to allow multiple files to be selected
            })
        .on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $k('#' + parent_id + ' #cate_img').attr('src',attachment.url);      
            $k('#' + parent_id + ' .cat_icon_url').val(attachment.url);
        })
       .open();
    });

    $k(document).on('keyup','.term-name-wrap #tag-name', function() {
        var slug = $k(this).val().toLowerCase();
        $k('.term-slug-wrap #tag-slug').val(slug);
    });

    $k(document).on('click', '.upload_bg_banner', function(evt) {
        var parent_id = $k(this).parent().attr('id');
        var custom_uploader;
        evt.preventDefault();
        var custom_uploader = wp.media({
            title: 'Banner Upload',
            button: {
                text: 'Upload Banner',
            },
            multiple: false
        })
        .on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            var sr = attachment.url.split(knw_baseurl_var.base).pop();
            $k('#' + parent_id + ' #banner_img').attr('src', attachment.url);
            $k('#' + parent_id + ' #banner-upload').val(attachment.url);
            $k('#' + parent_id + ' .knw_banner_url').val(sr);
        })
        .open();
    });

    $k(document).on('change', '#sel_webkul_knw_bg_color', function() {
        var clr = $k(this).val();
        $k('#webkul_knw_bg_color').val(clr);
    });

    $k(document).ready(function(){

        $k("#wk_knw_categories_list").sortable();

        $k('form.wk-cat-order-form').on('submit', function(evt) {
            evt.preventDefault();
            var order = $k('#wk_knw_categories_list').sortable('serialize');
            $k.ajax({
                type: "POST",
                url: knw_baseurl_var.url,
                data: "action=wk_update_category_order&"+order,
                success: function(data) {
                    $k('#wpbody-content').prepend('<div class="notice wk-cat-notice notice-success is-dismissible"><p>Updated Successfully !!!</p></div>');
                    $k(".notice.wk-cat-notice").fadeOut(3000);
                }
            });
        });

        $k("#wk_knw_categories_list").disableSelection();
    });

})($k);

