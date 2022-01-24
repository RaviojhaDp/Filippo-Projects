define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
    ], function($){
        'use strict';
        return function() {
            $.validator.addMethod(
                "zipValidationRule",
                 function (value, element) {
                  if($(element).attr('zipexist') == "true"){
                    return false;
                  }else{
                    return true;
                  }
                },
                    $.mage.__('Zipcode not in range')
                );
        }
    });