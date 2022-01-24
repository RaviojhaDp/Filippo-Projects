define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
    ], function($){
       
        'use strict';
        return function() {
           
            $.validator.addMethod(
                "eqpvalidationrule",
                function(value, element) {
                if($(element).attr('eqpexist') == "true"){
                    return false;
                  }else{
                    return true;
                  }
                },
                $.mage.__("This Equipment is already registered.")
                );

            $.validator.addMethod(
                "eightvalidate",
                function(value, element) {
                if($(element).attr('eqpexist') == "not_eight"){
                    return false;
                  }else{
                    return true;
                  }
                },
                $.mage.__("The equipment must consist of 8 characters.")
                );
        }
    });