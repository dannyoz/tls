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
                var subscription_status = jQuery('p.subscription_status');
                jQuery('#show_loading_update').css('display', 'none');
                jQuery('p.submit').prepend(response);
                if (hub_action_data.tls_hub_action == 'Subscribe') {
                    subscription_status.html('Subscribing');
                } else if (hub_action_data.tls_hub_action == 'Unsubscribe') {
                    subscription_status.html('Unsubscribing');
                }
                console.log(hub_action_data.tls_hub_action, subscription_status, response);
            }
        );

    });
});