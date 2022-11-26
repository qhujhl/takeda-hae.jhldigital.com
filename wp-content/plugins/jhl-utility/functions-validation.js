(function ($){
    window.validate_not_empty = function(val){
        if (jQuery.trim(val)) {
            return true;
        }
        return false;
    }

    window.validate_is_email = function(email){
        const regExp = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        return regExp.test(email);
    }

    window.validate_is_email_unique = function(email){
        //always return true as it is dummy for js validation
        return true;
    }

})(jQuery)
