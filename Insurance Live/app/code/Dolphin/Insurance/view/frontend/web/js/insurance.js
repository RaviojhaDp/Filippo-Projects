define([
    "jquery",
    "jquery/ui"
], function($){
    "use strict";
     
    function main(config, element) {
        $( "#client_default" ).prop( "checked", true );
        var $element = $(element);
        var AjaxUrl = config.AjaxUrl;
        var storeId = config.store;
        $('input[type=radio][name=group_id]').change(function(){
			var param_val = $(this).val();
			if(param_val == '3'){			
			var param = "retail";
			}
			if(param_val == '4'){			
			var param = "bot";
			}
			if(param_val == '5'){			
			var param = "client";
			}
                    $.ajax({
                        showLoader: true,
                        url: AjaxUrl,
                        data: { param: param, storeId: storeId },
                        type: "POST"
                    }).done(function (data) {
					    $('#fieldsetRepalce').replaceWith(data);
                        return true;
                    });
               
            });
    };
return main;
     }); 