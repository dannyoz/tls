jQuery(document).ready(function() {
    jQuery('.tls_hub_action').click(function(){
        var hub_action_data = {
            action: 'hub_action',
            tls_hub_action: jQuery(this).val()
        };
        jQuery('#show_loading_update').css('display', 'block');
        jQuery.post(
            tls_hub_action.ajax_admin,
            hub_action_data,
            function(response) {
                jQuery('#show_loading_update').css('display', 'none');
                jQuery('p.submit').prepend(response);
            }
        );

    });
});