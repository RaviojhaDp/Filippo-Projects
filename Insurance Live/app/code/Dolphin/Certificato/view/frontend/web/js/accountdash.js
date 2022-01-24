require(
            [
                'jquery',
                'mage/validation',
                'jquery/ui',
                'mage/translate'
            ],
            function ($) {
                'use strict';              	
                $(document).ready(function () {
                    $('#searchcertidash').on('keyup paste change', function () {
                    var AjaxUrl = "https://www.damianigroupcustomercare.com/certificato/index/searchcertidash/";
                       var param = $(this).val(); 
                    $.ajax({
                                showLoader: true,
                                url: AjaxUrl,
                                cache: false,
                                data: {data: param},
                                type: "POST",
                                dataType: 'html'
                            }).success(function (data) {
                            $('#tbodycertidash').html(data);
                    });
                  });
               });
            });

