define([
    "jquery",
    "jquery/ui"
], function($){
    "use strict";
 alert("SDFSDFSDFSD");
    function main(config, element) {
        var $element = $(element);
        var AjaxUrl = config.AjaxUrl;
        var CurrentGroupId = config.CurrentGroupId;
         
        $(document).ready(function(){
			alert("ADASDAS");
            setTimeout(function(){
                $.ajax({
                    context: '#ajaxresponse',
                    url: AjaxUrl,
                    type: "POST",
                    data: {CurrentGroupId:CurrentGroupId},
                }).done(function (data) {
					
                    $('#ajaxresponse').html(data.output);
                    return true;
                });
            },2000);
        });
 
 
    };
    return main;
});