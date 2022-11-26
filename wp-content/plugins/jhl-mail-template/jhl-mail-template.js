jQuery('#send').bind('click',function (){
    var email=jQuery('#send_email').val();
    var template_name=jQuery('#editable-post-name').text();
    jQuery(this).attr('disabled','disabled');
    jQuery(this).text('Sending...');
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: Ajax.ajaxurl,
        data:{
            action:'send_email',
            email:email,
            template:template_name
        },
        success: function (result) {
            if (result.code == 1) {
                jQuery('.success-info').show();
                jQuery('.success-info').text(result.msg);
                jQuery('#send').removeAttr('disabled');
                jQuery('#send').text('Send test email');
                setTimeout(function (){
                    jQuery('.success-info').hide();
                },5000)
            }else if (result.code == 0){
                jQuery('.error-info').show();
                jQuery('.error-info').text(result.msg);
                jQuery('#send').removeAttr('disabled');
                jQuery('#send').text('Send test email');
                setTimeout(function (){
                    jQuery('.error-info').hide();
                },5000)
            }

        },
        error : function() {
            jQuery('#send').removeAttr('disabled');
            jQuery('#send').text('Send test email');
            console.log("ajax error！");
        }
    });
})