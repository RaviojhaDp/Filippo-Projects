define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
    ], function($){
        'use strict';
        return function() {
            $.validator.addMethod(
                "emailvalidationruleit",
                function(value, element) {
                     if($(element).attr('emailexist') == "true"){
                      return false;
                  }else{
                     return true;
                  }
                },
                $.mage.__('Utente già registrato, per favore <a class="trigger-auth-popup" href="https://www.damianigroupcustomercare.com/index.php/customer/account/login/">ACCEDI</a>')
                );
        } 
    });