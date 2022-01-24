define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
    ], function($){
       
        'use strict';
        return function() {
           
            $.validator.addMethod(
                "iselectedvalidationrule",
                function(value, element) {
                if($(element).attr('isvalueselected') == "0"){
                    return false;
                  }else{
                    return true;
                  }
                },
                $.mage.__("This is a required field.")
                );
        }
    });