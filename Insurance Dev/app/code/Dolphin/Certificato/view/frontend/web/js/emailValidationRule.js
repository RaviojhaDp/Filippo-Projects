define([
    'jquery',
    'jquery/ui',
    'mage/url',
    'jquery/validate',
    'mage/translate'
    ], function($,urlBuilder){
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
                $.mage.__('User already registered, please <a class="trigger-auth-popup" href="https://certificato.luxmadein.it/customer/account/login/">LOGIN</a>')
                );
        } 
    });