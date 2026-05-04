function lbbNotEmpty(inputField) {

    var value = inputField.val();

    return value.trim() !== "";

}



function lbbIsEmpty(inputField) {

    var value = inputField.val();



    if (value === null || value === undefined) {

        return true;

    }



    return value.trim() === "";

}



function lbbIsValidEmail(emailField) {

    var email = emailField.val();

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    return emailRegex.test(email);

}



function lbbIsValidPhone(phoneField) {

    var phone = phoneField.val();

    var phoneRegex = /^[0-9]{10}$/;

    return phoneRegex.test(phone);

}



function showError(name,obj = false) {



    if(obj){

        name.show();

    }else{

        var errorDiv = name.attr('data-target-error');

        jQuery(errorDiv).css('display', 'block');

    }

    

}



function hideError(name) {

    var errorDiv = name.attr('data-target-error');

    jQuery(errorDiv).hide();

}



function lbbHideAllerrors(){

    jQuery('.lbb-error').hide();

}