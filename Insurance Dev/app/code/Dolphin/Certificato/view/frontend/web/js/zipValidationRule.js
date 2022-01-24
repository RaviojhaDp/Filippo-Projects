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
                    return true;
                  }else{
                    return false;
                  }
                },
                    $.mage.__('Please enter the correct zip code.')
                );
        }
    });