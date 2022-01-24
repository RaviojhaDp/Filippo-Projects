define([
    'jquery',
    'jquery/ui',
	'calMethod',
    'jquery/validate',
    'mage/translate'
    ], function($ , calMethod){
        'use strict';
        return function() {
            $.validator.addMethod(
                "calvalidationrule",
                function(value, element) {
               
                  if(value.substr(0, 2) == '51' || value.substr(0, 2) == '52'){
                    return true;
                  }else{
                    return false;
                  }
                },
                $.mage.__("Please verify the code. We checked it and it is not linked to the specific Brand. Please check on the Brand website or contact our Customer Service.")
                );
        }
    });