jQuery(document).ready(function() {

    var topicUrl = jQuery('input#topic_url').val();
    var hubUrl = jQuery('input#hub_url').val();

    if ( topicUrl !== '' && hubUrl !== '' ) {
        jQuery('.tls_hub_action').css('display', 'block');
    }

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
                var hub_message = jQuery('#hub-message');
                jQuery('#show_loading_update').css('display', 'none');

                if (hub_action_data.tls_hub_action == 'Subscribe') {
                    subscription_status.html('Subscribing');
                    if (response == true) {
                        hub_message.addClass('success').css('display', 'block');
                        hub_message.html('Your Subscription request is being processed. Check back later to see if you are fully Subscribed');
                    } else if ( response == false ) {
                        hub_message.addClass('fail').css('display', 'block');
                        hub_message.html('Error issuing Subscribe request to the Hub. Please make sure all your details are correct');
                    }

                }
            }
        );

    });
});