jQuery(document).ready(function($) {

    jQuery('#upload_classifieds_button').click(function() {

        window.send_to_editor = function(html) {
            console.log(html);
            var classifieds_pdf_url = jQuery(html).attr('href');
            jQuery('#classifieds_pdf_url').val(classifieds_pdf_url);
            tb_remove();
        };

        tb_show('Upload a Classifieds PDF', 'media-upload.php?referer=tls_theme_options&type=file&TB_iframe=true&post_id=0', false);
        return false;
    });

});