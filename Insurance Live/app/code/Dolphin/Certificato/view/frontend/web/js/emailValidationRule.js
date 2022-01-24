define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
    ], function($){
        'use strict';
        return function() {
            $.validator.addMethod(
                "emailvalidationrule",
                function(value, element) {
                     if($(element).attr('emailexist') == "true"){
                      return false;
                  }else{
                     return true;
                  }
                },
                $.mage.__('User already registered, please <a class="trigger-auth-popup" href="https://www.damianigroupcustomercare.com/index.php/customer/account/login/">LOGIN</a>')
                );
        } 
    });