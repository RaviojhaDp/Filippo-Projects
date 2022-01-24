define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
    ], function($){
       
        'use strict';
        return function() {
            $.validator.addMethod(
                "purchasedateValidationRule",
                 function (value, element) {
                    var date = value.split("/");
                    var d = parseInt(date[0], 10),
                    m = parseInt(date[1], 10),
                    y = parseInt(date[2], 10);
                    var nayaDate =  new Date(y, m - 1, d);
                    var currentTime = new Date();
                    var enDate = new Date(nayaDate);
                    var yearAgoTime = new Date().setFullYear(currentTime.getFullYear()-1);
                    var compDate2 = enDate - yearAgoTime;
                    
                    if (compDate2<0){
                        $('<div for="purchase_date" generated="true" class="mage-error purchseMsg" id="purchase_date-error" style="display: block;">'+ $.mage.__('The purchase date is greater than the maximum limit') +'</div>').insertAfter(".ui-datepicker-trigger");
                        return false;
                    }else{
                        $(".purchseMsg").remove();
                        return true;
                    }
                    
                  },
                    $.mage.__('Please enter a correct date')
                );
        }
    });