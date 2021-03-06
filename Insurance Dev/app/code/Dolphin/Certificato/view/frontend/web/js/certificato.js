    require(
                [
                    'jquery',
                    'mage/url',
                    'Magento_Ui/js/modal/modal',
                    'Magento_Customer/js/customer-data',
                    'ko',
                    'mage/calendar',
                    'mage/validation',
                    'steps',
                    'jquery/ui',
                    'mage/translate'
                ],
                function (
                        $,
                        urlBuilder,
                        modal,
                        customerData
                        ) {
                    'use strict';

                $(document).ready(function() {
                  $(window).keydown(function(event){
                    if(event.keyCode == 13) {
                      event.preventDefault();
                      return false;
                    }
                  });
                });

    			if ($('html')[0].lang == "it") {
                        var title = $.mage.__('Per sottoscrivere il certificato fai clic alla fine della pagina su Firma. È necessario firmare il documento due volte. Nel campo "nome" inserire solo il nome. Nel campo SMS è necessario mantenere il prefisso + 39 prima del numero di telefono. Completata la procedura cliccare sotto su "Salvare Documento".')
                        var text = $.mage.__('Salva documento')
                        var nextext = $.mage.__('PROCEDI')
                        var close_title = $.mage.__('Se clicchi X il documento verrà cancellato e il certificato non sarà sottoscritto')
    			 		} else {
                        var title = $.mage.__('To sign up for the certificate, click Sign at the bottom of the page. It is necessary to sign the document twice. In the "name" field enter only the name. In the SMS field it is necessary to keep the prefix + 39 before the telephone number. Once the procedure is complete click on "Save Document" below.')
                        var text = $.mage.__('Save Document')
                        var nextext = $.mage.__('PROCEED')
                        var close_title = $.mage.__('The document will be deleted if you click "X" and the certificate will not be subscribed.')
    				}


                    $(".show").hide();
                    var form = $("#example-advanced-form").show();
                    var dataForm = $('#example-advanced-form');
                    var ignore = null;
                    dataForm.mage('validation', {
                        ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden'
                    }).find('input:text').attr('autocomplete', 'off');

                    form.steps({
                        headerTag: "h3",
                        bodyTag: "fieldset",
                        transitionEffect: "slideLeft",
                        labels:
                                {
                                    next:nextext,

                                },

                        onStepChanging: function (event, currentIndex, newIndex)
                        {
                            if (currentIndex == '0') {
                                var validate = dataForm.validation('isValid');
                                var del_ds = $("#datepickers").val();
                                if (del_ds == '') {
                                    $("#datepickers").addClass("mage-error");
                                    $("#datepickers").next(".mage-error").css("display", "block");
                                    return false;
                                }

                                
                            }
                            if (currentIndex == '1') {
                                $(".have-an-account-main").hide();
                            }
                            
                            if (currentIndex == '2') {

	                                
                                return true;
                            }

                            // Allways allow previous action even if the current form is not valid!
                            if (currentIndex > newIndex)
                            {
                                return true;
                            }
                            // Forbid next action on "Warning" step if the user is to young
                            if (newIndex === 3 && Number($("#age-2").val()) < 18)
                            {
                                return false;
                            }
                            // Needed in some cases if the user went back (clean up)
                            if (currentIndex < newIndex)
                            {
                                // To remove error styles
                                form.find(".body:eq(" + newIndex + ") label.error").remove();
                                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                            }
                            form.validate().settings.ignore = ":disabled,:hidden";
                            return form.valid();
                        },
                        onStepChanged: function (event, currentIndex, priorIndex)
                        {
                            // Used to skip the "Warning" step if the user is old enough.
                            if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
                            {
                                form.steps("next");
                            }
                            // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                            if (currentIndex === 2 && priorIndex === 3)
                            {
                                form.steps("previous");
                            }
                        },
                        onFinishing: function (event, currentIndex)
                        {
						    var form = $(this);

						    // Disable validation on fields that are disabled.
						    // At this point it's recommended to do an overall check (mean ignoring only disabled fields)
						    form.validate().settings.ignore = ":disabled";

						    // Start validation; Prevent form submission if false
						    return form.valid();
						},
                        onFinished: function (event, currentIndex)
                        {
							 var form = $(this);

							    // Submit form input
							    form.submit();
                        }
                    });

                    $("#model-2").keyup(function () {
                        
                        var param_count = $(this).val().length;
                        if(param_count == '8'){
                            $(this).css("color","black");
                            $(this).attr("modeleightvalidate","true");
                        }else{
                           $(this).css("color","red");
                           $(this).attr("modeleightvalidate","false");
                         }
                     });

                    if($('html')[0].lang == "it") {

                        $.validator.addMethod(
                        "model_eightvalidate",
                        function(value, element) {
                        if($(element).attr('modeleightvalidate') == "false"){
                            return false;
                          }else{
                            return true;
                          }
                        },
                        $.mage.__("Il codice materiale deve essere composto da 8 caratteri")
                        );
                    }else{
                        $.validator.addMethod(
                        "model_eightvalidate",
                        function(value, element) {
                        if($(element).attr('modeleightvalidate') == "false"){
                            return false;
                          }else{
                            return true;
                          }
                        },
                        $.mage.__("The product code must consist of 8 characters.")
                        );                        

                    }
                    /*Model-2 latest*/


                    var keyPressTimeout;  
                    $("#equpiment-2").keyup(function () {
                      var param_count = $(this).val().length;
                        if(param_count == '8'){
                            $(this).css("color","black");
                           // $(this).attr("eightvalidate","true");
                        }else{
                           $(this).css("color","red");
                          // $(this).attr("eightvalidate","false");
                       }
                         clearTimeout(keyPressTimeout);
                        var param = $(this).val();
                        var AjaxUrl = urlBuilder.build('certificato/index/equipment');
                        keyPressTimeout = setTimeout( function() { 
                            $.ajax({
                            showLoader: true,
                            url: AjaxUrl,
                            //global: false,
                            data: {data: param},
                            cache: false,
                            type: "POST",
                            dataType: 'json'
                        }).done(function (data) {
                        	
                        	 if (data.datas == 1 ) {
                        	 	 $("#equpiment-2").attr("eqpexist", "not_eight");
                        	 }
                            else if(data.data == true) {
                                $("#equpiment-2").attr("eqpexist", "true");
                            } else {
                                $("#equpiment-2").attr("eqpexist", "false");
                                $('#example-advanced-form #model-2 ,#example-advanced-form #serial_number-2,#example-advanced-form #seller_name-2,#example-advanced-form #purchase_date-2,#example-advanced-form #fileToUpload,#example-advanced-form #general-conditions,#example-advanced-form #privacy,#example-advanced-form #marketing,#example-advanced-form #profilazione,#example-advanced-form #cessione').attr("disabled", false);
                            }
                        }); }, 1000);    
                    });

                    


                    $("#click-mes").on('click', function (e) {
                        e.preventDefault(); 
                        var newUrl = urlBuilder.build($("input[name=brand]").val() + "/assicurazione.html?" + $("#example-advanced-form").serialize() + "&flag=2&region=0");
                        var validate = dataForm.validation('isValid');
                         if (validate) {
                       var paramsu = $("#email_address").val()
                        $("#popup_email").text(paramsu);
                        $("#popup_url").attr("href", newUrl);
                    /*---------------INSURANCE EMAIL CONFIRM EMAIL----------*/ 
                        //window.location.href = newUrl;
                                //$(".shownamebr").css("display","none");
                                var ajaxU = urlBuilder.build('certificato/index/certi');
                                var getUrlParameter = function getUrlParameter(sParam) {
                                    var sPageURL = window.location.search.substring(1),
                                            sURLVariables = sPageURL.split('&'),
                                            sParameterName,
                                            i;
                                    for (i = 0; i < sURLVariables.length; i++) {
                                        sParameterName = sURLVariables[i].split('=');
                                        if (sParameterName[0] === sParam) {
                                            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                                        }
                                    }
                                };
                                var renewal = getUrlParameter('renewal');
                                if (renewal == '1') {
                                    var ajaxU = urlBuilder.build('certificato/index/certirenewal');
                                }

                                    var param = $("#example-advanced-form").serializeArray();
                                    var form_data = new FormData();
                                    //form_data.append('filetoupload', $('input[type=file]')[0].files[0]);
                                    $.each(param, function (key, input) {
                                        form_data.append(input.name, input.value);
                                    });

                                    $.ajax({
                                        //showLoader: true,
                                        type: 'POST',
                                        url: ajaxU,
                                        //global: false,
                                        data: form_data,
                                        contentType: false,
                                        processData: false,
                                        async: false,
                                        cache: false,
                                        timeout: 30000,
                                        success: function (data) {                                            
                                            console.log("----rv certi response---");
                                            var newUrl = urlBuilder.build(data.brand+'/assicurazione.html');
                                            var redirectUrl = urlBuilder.build('customer/account/login/');
                                            if (data.status === 'true')
                                            {
                                               var certificato_code =  data.certificato_code;
                                                    var AjaxUrl1 = urlBuilder.build('insurancepdf/index/email');
                                                        $.ajax({
                                                            showLoader: true,
                                                            url: AjaxUrl1,
                                                            data: {email:data.email,url:urlBuilder.build('insurancepdf/index/confirm?certid='+certificato_code+'&lang='+$('html')[0].lang),certificato_code: certificato_code,lang:$('html')[0].lang},
                                                            cache: false,
                                                            type: "POST"
                                                        }).done(function (data) {

                                                        var AjaxUrl = urlBuilder.build('certificato/index/emailexist');
                                                            var param = data.email;
                                                            if(param != '' || param != null)
                                                            {
                                                            $.ajax({
                                                                showLoader: true,
                                                                url: AjaxUrl,
                                                                global: false,
                                                                cache: false,
                                                                data: {data: param},
                                                                type: "POST",
                                                                dataType: 'json'
                                                            }).done(function (data) {
                                                                if (data.data == true) {
                                                                    $("#email_address").attr("emailexist", "true");
                                                                    var storeName = $(".storename").text();
                                                                    $("#email_address").attr("store", storeName);
                                                                } else {
                                                                    var AjaxUrl = urlBuilder.build('certificato/index/extendcreate');
                                                                    var param = $("#example-advanced-form").serialize()+ "&certificato_code=" + certificato_code;
                                                                    $.ajax({
                                                                        //showLoader: true,
                                                                        url: AjaxUrl,
                                                                        //global: false,
                                                                        cache: false,
                                                                        data: param,
                                                                        type: "POST"
                                                                    }).done(function (data) {
                                                                       console.log("customer create succssfully");
                                                                    });
                                                                }
                                                            });
                                                            }
                                                        //window.location.href = redirectUrl;
                                                        var options = {
                                                        type: 'popup',
                                                        clickableOverlay: false,
                                                        modalClass: 'active_insurance_modal',
                                                        responsive: true,
                                                        innerScroll: true,
                                                        buttons: [{
                                                            text: $.mage.__('CLOSE'),
                                                            class: 'mymodal1',
                                                            click: function () {
                                                             window.location.href = urlBuilder.build('customer/account/login/');               
                                                           }
                                                        }]
                                                    };
                                                    var popup = modal(options, $('#popup-modal-step3'));
                                                    $("#popup-modal-step3").modal(options).modal("openModal");
                    /*---------------INSURANCE EMAIL CONFIRM EMAIL----------*/
                                                });
                                            } else {
                                                $(window).scrollTop(0);
                                                customerData.set('messages', {
                                                    messages: [{
                                                            type: 'error',
                                                            text: data.message
                                                        }]
                                                });
                                            }
                                        }
                                    });
                                                



                    }
                });


                    /*Cities Validate End*/
                    $('#state').on('change', function () {
                                       var AjaxUrl2 = urlBuilder.build('certificato/index/autoloadcity');
                                       var region_code = $("#state").val()
                                       //var zip = $("#zipcode-2").val();
                                        $.ajax({
                                            showLoader: true,
                                            url: AjaxUrl2,
                                            //global: false,
                                            cache: false,
                                            data: {region_code,region_code},
                                            type: "POST"
                                        }).done(function (data) {
                                            $(".cityselect #city-2").replaceWith(data);
                                       });
                                    });
                    /*Cities Validate End*/


                    if($("#name_boutique_retailer-2").is("input")){
                    var keyPressTimeout;
                    $('#name_boutique_retailer-2').on('keyup paste change', function () {
                        if($(this).next().attr("id") == "name_boutique_retailer-2-error"){
                            $("#name_boutique_retailer-2-error").hide();
                        }
                       $("#name_boutique_retailer-2").attr("IsValueSelected","0");
                       clearTimeout(keyPressTimeout);
                       var AjaxUrl2 = urlBuilder.build('certificato/index/autoloadnameboutailer');
                       var val = $(this).val();
                        keyPressTimeout = setTimeout( function() {
                         $.ajax({
                            showLoader: true,
                            url: AjaxUrl2,
                            //global: false,
                            cache: false,
                            data: {param: val},
                            type: "POST"
                        }).done(function (data) {
                         $('.shownamebr').css("display","block");
                         $("#autosuggestbr").html(data);
                         $(".result").click(function (e) {
							//$(".shownamebr").css("display","none");
                            $("#name_boutique_retailer-2").attr("IsValueSelected","1");
                         e.preventDefault();
                         var AjaxUrl2 = urlBuilder.build('certificato/index/autoloadboutailer');
                         var text = $(this).text();
                         var val = $(this).attr("data-value");
                         $('#name_boutique_retailer_hidden').val(val);
                         $('#name_boutique_retailer-2').val(text);
                         $(".shownamebr").hide();
                        $.ajax({
                            showLoader: true,
                            url: AjaxUrl2,
                            //global: false,
                            cache: false,
                            data: {param: val},
                            type: "POST"
                        }).done(function (data) {
							$("#autosuggestbr").css("display","none");
                            $(".loading-mask").css('display', 'none');
                            //var obj = JSON.parse(data);
                            var obj = JSON.parse(JSON.stringify(data));
                            var streets = obj[0].street;
                            if (streets != '') {
                                $('#add_boutique_retailer-2').val(streets);
                            	}
                       		 });
                          });
                       });
     				}, 1000);

                    });
                  }else{
                    var keyPressTimeout;
                  	$('#name_boutique_retailer-2').on('keyup paste change', function () {
                        $("#name_boutique_retailer-2").attr("IsValueSelected","0");
                        clearTimeout(keyPressTimeout);
                       var AjaxUrl2 = urlBuilder.build('certificato/index/autoloadboutailer');
                        var val = $("#name_boutique_retailer-2 option:selected").val();
                        $(".showboutailer").hide();
                        keyPressTimeout = setTimeout( function() {
                            $.ajax({
                            showLoader: true,
                            //global: false,
                            url: AjaxUrl2,
                            cache: false,
                            data: {param: val},
                            type: "POST"
                        }).done(function (data) {
                            $(".loading-mask").css('display', 'none');
                            //var obj = JSON.parse(data);
                            var obj = JSON.parse(JSON.stringify(data));
                            var streets = obj[0].street;
                            if (streets != '') {
                                $('#add_boutique_retailer-2').val(streets);
                            }
                        });
                        }, 1000);
                    });
                  }
                  
                  $('#datepickers_it_single, #wedding_anniversary_it , #datepickers_it_married').datepicker({
                        closeText: "Chiudi",
                        prevText: "&#x3C;Prec",
                        nextText: "Succ&#x3E;",
                        currentText: "Oggi",
                        monthNames: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno",
                            "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
                        monthNamesShort: ["Gen", "Feb", "Mar", "Apr", "Mag", "Giu",
                            "Lug", "Ago", "Set", "Ott", "Nov", "Dic"],
                        dayNames: ["Domenica", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato"],
                        dayNamesShort: ["Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab"],
                        dayNamesMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
                        weekHeader: "Sm",
                        dateFormat: "dd/mm/yy",
                        showOn: "both",
                        buttonImage: urlBuilder.build("/media/ICON-CALEDAR-NEW.svg"),
                        buttonImageOnly: true,
                        firstDay: 1,
                        changeMonth: true,
                        isRTL: false,
                        showMonthAfterYear: false,
                        changeYear: true,
                        yearRange: "-99:+0",
                        yearSuffix: "",
                        minDate: '-100Y',
                        maxDate: '0D'
                    });

                  $('#first_chidren_dob_six, #engaged_chidren_dob_six,#first_chidren_dob_five, #engaged_chidren_dob_five,#first_chidren_dob_four, #engaged_chidren_dob_four,#first_chidren_dob_three, #engaged_chidren_dob_three,#first_chidren_dob_two, #engaged_chidren_dob_two,#first_chidren_dob_one, #engaged_chidren_dob_one, #datepickers_single, #wedding_anniversary ,#datepickers_married,#chidren_dob_one ,#chidren_dob_two  ,#chidren_dob_three ,#chidren_dob_four  ,#chidren_dob_five ,#chidren_dob_six').datepicker({
                        prevText: '&#x3c;zurück', prevStatus: '',
                        prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
                        nextText: 'Vor&#x3e;', nextStatus: '',
                        nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
                        dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                        showMonthAfterYear: false,
                        showOn: "both",
                        //maxDate: new Date(),
                        buttonImage: urlBuilder.build("/media/ICON-CALEDAR-NEW.svg"),
                        buttonImageOnly: true,
                        dateFormat: 'dd/mm/yy',
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-99:+0",
                        minDate: '-100Y',
                        maxDate: '0D'
                    });
                    $('#datepickers_it').datepicker({
                        closeText: "Chiudi",
                        prevText: "&#x3C;Prec",
                        nextText: "Succ&#x3E;",
                        currentText: "Oggi",
                        monthNames: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno",
                            "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
                        monthNamesShort: ["Gen", "Feb", "Mar", "Apr", "Mag", "Giu",
                            "Lug", "Ago", "Set", "Ott", "Nov", "Dic"],
                        dayNames: ["Domenica", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato"],
                        dayNamesShort: ["Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab"],
                        dayNamesMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
                        weekHeader: "Sm",
                        dateFormat: "dd/mm/yy",
                        showOn: "both",
                        buttonImage: urlBuilder.build("/media/ICON-CALEDAR-NEW.svg"),
                        buttonImageOnly: true,
                        firstDay: 1,
                        changeMonth: true,
                        isRTL: false,
                        showMonthAfterYear: false,
                        changeYear: true,
                        yearRange: "-99:+0",
                        yearSuffix: "",
                        minDate: '-100Y',
                        maxDate: '-0Y'
                    });
                    $('#datepickers').datepicker({
                        prevText: '&#x3c;zurück', prevStatus: '',
                        prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
                        nextText: 'Vor&#x3e;', nextStatus: '',
                        nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
                        dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                        showMonthAfterYear: false,
                        showOn: "both",
                        //maxDate: new Date(),
                        buttonImage: urlBuilder.build("/media/ICON-CALEDAR-NEW.svg"),
                        buttonImageOnly: true,
                        dateFormat: 'dd/mm/yy',
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-99:+0",
                        minDate: '-100Y',
                        maxDate: '-18Y'
                    });
                    $('#purchase_date_datepickers_it').datepicker({
                        closeText: "Chiudi",
                        prevText: "&#x3C;Prec",
                        nextText: "Succ&#x3E;",
                        currentText: "Oggi",
                        monthNames: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno",
                            "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
                        monthNamesShort: ["Gen", "Feb", "Mar", "Apr", "Mag", "Giu",
                            "Lug", "Ago", "Set", "Ott", "Nov", "Dic"],
                        dayNames: ["Domenica", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato"],
                        dayNamesShort: ["Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab"],
                        dayNamesMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
                        weekHeader: "Sm",
                        dateFormat: "dd/mm/yy",
                        showOn: "both",
                        buttonImage: urlBuilder.build("/media/ICON-CALEDAR-NEW.svg"),
                        buttonImageOnly: true,
                        firstDay: 1,
                        changeMonth: true,
                        isRTL: false,
                        showMonthAfterYear: false,
                        yearSuffix: "",
                        changeYear: true,
                        yearRange: "-40:+0",
                        minDate: '-61D',
                        maxDate: '+0D'
                    });
                    $('#purchase_date_datepickers').datepicker({
                        prevText: '&#x3c;zurück', prevStatus: '',
                        prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
                        nextText: 'Vor&#x3e;', nextStatus: '',
                        nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
                        dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                        showMonthAfterYear: false,
                        showOn: "both",
                        buttonImage: urlBuilder.build("/media/ICON-CALEDAR-NEW.svg"),
                        buttonImageOnly: true,
                        dateFormat: "dd/mm/yy",
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-40:+0",
                        minDate: '-61D',
                        maxDate: '+0D'
                    });
                    $(document).on('change', '#datepicker', function () {

                        $(this).removeClass("mage-error");
                        $(this).siblings(".mage-error").css("display", "none");
                        var dateObject = $("#datepickers").datepicker('getDate');
                        $.datepicker.formatDate('dd MM, yy', dateObject);
                        var dateVariable = $(this).val();
                        var a = new Date();
                        var d = new Date();
                        d.setMonth(d.getMonth() - 1);

                        if (Date.parse(dateVariable) <= Date.parse(a) && Date.parse(dateVariable) >= Date.parse(d)) {
                            $(this).siblings(".mage-error").css("display", "none");
                            return true;

                        } else
                        {
                            $(this).val('');
                            $(this).siblings(".mage-error").text("Purchase Date within month is only valid.");
                            $(this).siblings(".mage-error").css("display", "block");
                            return false;
                        }
                    });

                    $('#civil_status').on('keyup paste change', function () {

                        if($(this).val()== '1'){ //Single
                           
                             $("#first_no_of_child").val(7);
                             for ( var i = 0 ; i < 7 ; i++ ) {
                                var id = ".first_childofchild_"+i;
                                $(id).each(function() {   
                                $(".first_childofchild_"+i).css("display","none")
                                });
                             }
                             for ( var i = 0 ; i < 7 ; i++ ) {
                                var id = ".childofchild_"+i;
                                $(id).each(function() {   
                                $(".childofchild_"+i).css("display","none")
                                });
                             }
                             for ( var i = 0 ; i < 7 ; i++ ) {
                                var id = ".first_childofchild_"+i;
                                $(id).each(function() {   
                                $(".engaged_childofchild_"+i).css("display","none")
                                });
                             }
                            $(".statusfirstchild").css("display","block");
                        //}else{
                            $(".statusmarriedchild").css("display","none");
                             $(".statussinglechild").css("display","none");
                        }

                        if($(this).val()== '5'){ //Engaged
                             $("#engaged_no_of_child").val(7);
                            $(".statussinglechild").css("display","block");
                            for ( var i = 0 ; i < 7 ; i++ ) {
                            var id = ".childofchild_"+i;
                            $(id).each(function() {   
                            $(".childofchild_"+i).css("display","none")
                            });
                         }
                        //}else{
                            $(".statusmarriedchild").css("display","none");
                            $(".statusfirstchild").css("display","none");
                           	for ( var i = 0 ; i < 7 ; i++ ) {
                                var id = ".childofchild_"+i;
                                $(id).each(function() {   
                                $(".childofchild_"+i).css("display","none")
                                });
                             }
                            for ( var i = 0 ; i < 7 ; i++ ) {
                                var id = ".first_childofchild_"+i;
                                $(id).each(function() {   
                                $(".first_childofchild_"+i).css("display","none")
                                });
                             }
                             for ( var i = 0 ; i < 7 ; i++ ) {
                                var id = ".childofchild_"+i;
                                $(id).each(function() {   
                                $(".childofchild_"+i).css("display","none")
                                });
                             }
                        }

                        

                        if($(this).val()== '2'){ //Married
                           
                             $("#no_of_child").val(7);
                             for ( var i = 0 ; i < 7 ; i++ ) {
                                var id = ".childofchild_"+i;
                                $(id).each(function() {   
                                $(".childofchild_"+i).css("display","none")
                                });
                             }
                             for ( var i = 0 ; i < 7 ; i++ ) {
                                var id = ".engaged_childofchild_"+i;
                                $(id).each(function() {   
                                $(".engaged_childofchild_"+i).css("display","none")
                                });
                             }
                             for ( var i = 0 ; i < 7 ; i++ ) {
                                var id = ".first_childofchild_"+i;
                                $(id).each(function() {   
                                $(".first_childofchild_"+i).css("display","none")
                                });
                             }
                            $(".statusmarriedchild").css("display","block");
                            $(".statussinglechild").css("display","none");
                            $(".statusfirstchild").css("display","none");
                        }
                    });

                    $('#no_of_child').on('keyup paste change', function () {
                     var v = $(this).val();            
                       for ( var i = v; i >= 0 ; i-- ) {
                           var id = ".childofchild_"+i;
                           $(id).each(function() {
                           $(".childofchild_"+i).css("display","block");
                        });
                       } 

                       var vv = parseInt($(this).val()) + parseInt(1);
                       for ( var i = vv ; i < 7 ; i++ ) {
                            var id = ".childofchild_"+i;
                            $(id).each(function() {   
                            $(".childofchild_"+i).css("display","none")
                            });
                        }
                     });

                    $('#engaged_no_of_child').on('keyup paste change', function () {
                     var v = $(this).val();            
                       for ( var i = v; i >= 0 ; i-- ) {
                           var id = ".engaged_childofchild_"+i;
                           $(id).each(function() {
                           $(".engaged_childofchild_"+i).css("display","block");
                        });
                       } 

                       var vv = parseInt($(this).val()) + parseInt(1);
                       for ( var i = vv ; i < 7 ; i++ ) {
                            var id = ".engaged_childofchild_"+i;
                            $(id).each(function() {   
                            $(".engaged_childofchild_"+i).css("display","none")
                            });
                        }
                     });

                    $('#first_no_of_child').on('keyup paste change', function () {
                     var v = $(this).val();            
                       for ( var i = v; i >= 0 ; i-- ) {
                           var id = ".first_childofchild_"+i;
                           $(id).each(function() {
                           $(".first_childofchild_"+i).css("display","block");
                        });
                       } 

                       var vv = parseInt($(this).val()) + parseInt(1);
                       for ( var i = vv ; i < 7 ; i++ ) {
                            var id = ".first_childofchild_"+i;
                            $(id).each(function() {   
                            $(".first_childofchild_"+i).css("display","none")
                            });
                        }
                     });
                    
     //});

                    $("#autosuggest_close").on('click', function () {
                        $(".show").hide();
                        $(".show-status").hide();
                    });
                    $(".autosuggest_close").on('click', function () {
                         $(".shownamebr").hide();
                    });

                    $('#customername-2').on('keyup paste change', function () {
                        var key = $(this).val();
                        var AjaxUrl = urlBuilder.build('certificato/index/autosuggest');
                        $.ajax({
                            showLoader: true,
                            //global: false,
                            url: AjaxUrl,
                            cache: false,
                            data: key,
                            type: "POST"
                        }).done(function (data) {
                            $(".show").show();
                            $("#autosuggest").html(data);
                            $(".result").click(function (e) {
                                e.preventDefault();
                                var AjaxUrl2 = urlBuilder.build('certificato/index/autoload');
                                var text = $(this).text();
                                var val = $(this).attr("data-value");
                                $("#customername-2").val(text);
                                $(".show").hide();
                                $.ajax({
                                    showLoader: true,
                                    url: AjaxUrl2,
                                    cache: false,
                                    data: {param: val},
                                    type: "POST"
                                }).done(function (data) {
                                    $(".cityinput").css("display","block");
                                    $(".cityselect").css("display","none");
                                    var obj = JSON.parse(JSON.stringify(data));
                                    var certificato_id = obj.certificato_id;
                                    var customer_group_id = obj.customer_group_id;
                                    var name = obj.name;
                                    if (name != '') {
                                        $('#name-2').val(name);
                                    }
                                    var surname = obj.surname;
                                    if (surname != '') {
                                        $('#surname-2').val(surname);
                                    }
                                    var address = obj.address;
                                    if (address != '') {
                                        $('#address-2').val(address);
                                    }
                                    var zipcode = obj.zipcode;
                                    if (name != '') {
                                        $('#zipcode-2').val(zipcode);
                                    }
                                    var city = obj.city;
                                    if (city != '') {

                                        $('#city-2').val(city);
                                    }
                                    var region = obj.region;
                                    if (region != '') {

                                        if(obj.country_id == "IT"){

                                          $('.field.states.required').hide();
                                          $('.field.region.required').show();
                                          $('#state option').each(function () {
                                         if($(this).text() == region || $(this).text() == region+"." || $(this).text() == region+" "){
                                          var current_id = $(this).val();
                                          $(this).attr("selected","selected");  
                                           }
                                          });
                                        }else{
                                           $('.field.states.required').show();
                                           $('.field.region.required').hide();
                                           $('#states').val(region); 
                                        }
                                       // $('#state').val(region);
                                        
                                        
                                    }


                                    var country_id = obj.country_id;
                                    if (country_id != '') {
                                        $('#country option').each(function () {
                                         if($(this).val() == country_id ){
                                          $(this).attr("selected","selected");

                                         }
                                      });
                                    }

                                    var email = obj.email;
                                    if (email != '') {
                                        $('#email_address').val(email);
                                    }
                                    var phone = obj.phone;
                                    if (phone != '') {
                                        $('#phone-2').val(phone);
                                    }
                                    var mobile_phone = obj.mobile_phone;

                                    if (mobile_phone != '') {
                                        $('#mobile_phone-2').val(mobile_phone);
                                    }
                                    var fiscal_code = obj.fiscal_code;
                                    //alert(fiscal_code);
                                    if (fiscal_code != '' && fiscal_code != '0000') {
                                        $('#fiscal-code-2').val(fiscal_code);
                                    }
                                    var dob = obj.dob;
                                    if (dob != '') {

                                        $('#datepickers').datepicker('setDate', dob);
                                        $('#datepickers_it').datepicker('setDate', dob);

                                    }
                                    // need to set
                                    var gender = obj.sex;
                                    if (gender != '') {
                                        $('#gender').val(gender);
                                    }
                                    var civil_status = obj.civil_status;
                                    if (civil_status != '' || civil_status != null) {

                                        if(civil_status == '1'){ //SINGLE

                                            $(".statusfirstchild").css("display","block");
                                            $(".statusmarriedchild").css("display","none");
                                            for ( var i = 0 ; i < 7 ; i++ ) {
                                                var id = ".childofchild_"+i;
                                                $(id).each(function() {   
                                                $(".childofchild_"+i).css("display","none");
                                                $(".statusmarriedchild").children().children().val("");
                                                });
                                             }
                                            $(".statussinglechild").css("display","none");
                                            for ( var i = 0 ; i < 7 ; i++ ) {
                                                var id = ".engaged_childofchild_"+i;
                                                $(id).each(function() {   
                                                $(".engaged_childofchild_"+i).css("display","none");
                                                $(".statussinglechild").children().children().val("");
                                                });
                                             }
                                            //FirstChildrenStart
                                            var first_no_of_child = obj.first_no_of_child;
                                            if (first_no_of_child != ''){
                                                $(".first_no_of_child").val(first_no_of_child);
                                                 var v = first_no_of_child;            
                                                   for ( var i = v; i >= 0 ; i-- ) {
                                                       var id = ".first_childofchild_"+i;
                                                       $(id).each(function() {
                                                       $(".first_childofchild_"+i).css("display","block");
                                                    });
                                                   } 

                                                   var vv = parseInt(first_no_of_child) + parseInt(1);
                                                   for ( var i = vv ; i < 7 ; i++ ) {
                                                        var id = ".first_childofchild_"+i;
                                                        $(id).each(function() {   
                                                        $(".first_childofchild_"+i).css("display","none")
                                                        });
                                                    }

                                                var first_chidren_name_one = obj.first_chidren_name_one;
                                                if (first_chidren_name_one != ''){
                                                    //alert(first_chidren_name_one);
                                                    $(".first_chidren_name_one").val(first_chidren_name_one);
                                                }
                                                var first_chidren_surname_one = obj.first_chidren_surname_one;
                                                if (first_chidren_surname_one != ''){
                                                    $(".first_chidren_surname_one").val(first_chidren_surname_one);
                                                }
                                                var first_chidren_dob_one = obj.first_chidren_dob_one;
                                                if (first_chidren_dob_one != ''){
                                                    $('#first_chidren_dob_one').datepicker('setDate', first_chidren_dob_one);
                                                }  
                                                var first_chidren_name_two = obj.first_chidren_name_two;
                                                if (first_chidren_name_two != ''){
                                                    $(".first_chidren_name_two").val(first_chidren_name_two);
                                                }
                                                var first_chidren_surname_two = obj.first_chidren_surname_two;
                                                if (first_chidren_surname_two != ''){
                                                    $(".first_chidren_surname_two").val(first_chidren_surname_two);
                                                }
                                                var first_chidren_dob_two = obj.first_chidren_dob_two;
                                                if (first_chidren_dob_two != ''){
                                                    $('#first_chidren_dob_two').datepicker('setDate', first_chidren_dob_two);
                                                }  

                                                var first_chidren_name_three = obj.first_chidren_name_three;
                                                if (first_chidren_name_three != ''){
                                                    $(".first_chidren_name_three").val(first_chidren_name_three);
                                                }
                                                var first_chidren_surname_three = obj.first_chidren_surname_three;
                                                if (first_chidren_surname_three != ''){
                                                    $(".first_chidren_surname_three").val(first_chidren_surname_three);
                                                }
                                                var first_chidren_dob_three = obj.first_chidren_dob_three;
                                                if (first_chidren_dob_three != ''){
                                                    $('#first_chidren_dob_three').datepicker('setDate', first_chidren_dob_three);
                                                }  

                                                var first_chidren_name_four = obj.first_chidren_name_four;
                                                if (first_chidren_name_four != ''){
                                                    $(".first_chidren_name_four").val(first_chidren_name_four);
                                                }
                                                var first_chidren_surname_four = obj.first_chidren_surname_four;
                                                if (first_chidren_surname_four != ''){
                                                    $(".first_chidren_surname_four").val(first_chidren_surname_four);
                                                }
                                                var first_chidren_dob_four = obj.first_chidren_dob_four;
                                                if (first_chidren_dob_four != ''){
                                                    $('#first_chidren_dob_four').datepicker('setDate', first_chidren_dob_four);
                                                } 

                                                var first_chidren_name_five = obj.first_chidren_name_five;
                                                if (first_chidren_name_five != ''){
                                                    $(".first_chidren_name_five").val(first_chidren_name_five);
                                                }
                                                var first_chidren_surname_five = obj.first_chidren_surname_five;
                                                if (first_chidren_surname_five != ''){
                                                    $(".first_chidren_surname_five").val(first_chidren_surname_five);
                                                }
                                                var first_chidren_dob_five = obj.first_chidren_dob_five;
                                                if (first_chidren_dob_five != ''){
                                                    $('#first_chidren_dob_five').datepicker('setDate', first_chidren_dob_five);
                                                } 

                                                var first_chidren_name_six = obj.first_chidren_name_six;
                                                if (first_chidren_name_six != ''){
                                                    $(".first_chidren_name_six").val(first_chidren_name_six);
                                                }
                                                var first_chidren_surname_six = obj.first_chidren_surname_six;
                                                if (first_chidren_surname_six != ''){
                                                    $(".first_chidren_surname_six").val(first_chidren_surname_six);
                                                }
                                                var first_chidren_dob_six = obj.first_chidren_dob_six;
                                                if (first_chidren_dob_six != ''){
                                                    $('#first_chidren_dob_six').datepicker('setDate', first_chidren_dob_six);
                                                } 

                                            }    
                                            //FIRSTChildrenEnd
                                        }

                                    	if(civil_status == '5'){ // Engaged
                                    		$(".statussinglechild").css("display","block");

                                    		var partner_name_single = obj.partner_name_single;
                                    		if (partner_name_single != ''){
                                    			$(".partner_name_single").val(partner_name_single);
                                    		}
                                    		var partner_surname_single = obj.partner_surname_single;
                                    		if (partner_surname_single != ''){
                                    			$(".partner_surname_single").val(partner_surname_single);
                                    		}
                                    		var partner_dob_single = obj.partner_dob_single;
                                    		if (partner_dob_single != ''){
                                    			$("#datepickers_single, #datepickers_it_single").datepicker('setDate', partner_dob_single);
                                    		}

                                            //EngagedChildrenStart
                                            var engaged_no_of_child = obj.engaged_no_of_child;
                                            //alert(engaged_no_of_child);
                                            if (engaged_no_of_child != ''){
                                                $(".engaged_no_of_child").val(engaged_no_of_child);
                                                 var v = engaged_no_of_child;            
                                                   for ( var i = v; i >= 0 ; i-- ) {
                                                       var id = ".engaged_childofchild_"+i;
                                                       $(id).each(function() {
                                                       $(".engaged_childofchild_"+i).css("display","block");
                                                    });
                                                   } 

                                                   var vv = parseInt(engaged_no_of_child) + parseInt(1);
                                                   for ( var i = vv ; i < 7 ; i++ ) {
                                                        var id = ".engaged_childofchild_"+i;
                                                        $(id).each(function() {   
                                                        $(".engaged_childofchild_"+i).css("display","none")
                                                        });
                                                    }

                                                var engaged_chidren_name_one = obj.engaged_chidren_name_one;
                                                if (engaged_chidren_name_one != ''){
                                                    //alert(engaged_chidren_name_one);
                                                    $(".engaged_chidren_name_one").val(engaged_chidren_name_one);
                                                }
                                                var engaged_chidren_surname_one = obj.engaged_chidren_surname_one;
                                                if (engaged_chidren_surname_one != ''){
                                                    $(".engaged_chidren_surname_one").val(engaged_chidren_surname_one);
                                                }
                                                var engaged_chidren_dob_one = obj.engaged_chidren_dob_one;
                                                if (engaged_chidren_dob_one != ''){
                                                    $('#engaged_chidren_dob_one').datepicker('setDate', engaged_chidren_dob_one);
                                                }  
                                                var engaged_chidren_name_two = obj.engaged_chidren_name_two;
                                                if (engaged_chidren_name_two != ''){
                                                    $(".engaged_chidren_name_two").val(engaged_chidren_name_two);
                                                }
                                                var engaged_chidren_surname_two = obj.engaged_chidren_surname_two;
                                                if (engaged_chidren_surname_two != ''){
                                                    $(".engaged_chidren_surname_two").val(engaged_chidren_surname_two);
                                                }
                                                var engaged_chidren_dob_two = obj.engaged_chidren_dob_two;
                                                if (engaged_chidren_dob_two != ''){
                                                    $('#engaged_chidren_dob_two').datepicker('setDate', engaged_chidren_dob_two);
                                                }  

                                                var engaged_chidren_name_three = obj.engaged_chidren_name_three;
                                                if (engaged_chidren_name_three != ''){
                                                    $(".engaged_chidren_name_three").val(engaged_chidren_name_three);
                                                }
                                                var engaged_chidren_surname_three = obj.engaged_chidren_surname_three;
                                                if (engaged_chidren_surname_three != ''){
                                                    $(".engaged_chidren_surname_three").val(engaged_chidren_surname_three);
                                                }
                                                var engaged_chidren_dob_three = obj.engaged_chidren_dob_three;
                                                if (engaged_chidren_dob_three != ''){
                                                    $('#engaged_chidren_dob_three').datepicker('setDate', engaged_chidren_dob_three);
                                                }  

                                                var engaged_chidren_name_four = obj.engaged_chidren_name_four;
                                                if (engaged_chidren_name_four != ''){
                                                    $(".engaged_chidren_name_four").val(engaged_chidren_name_four);
                                                }
                                                var engaged_chidren_surname_four = obj.engaged_chidren_surname_four;
                                                if (engaged_chidren_surname_four != ''){
                                                    $(".engaged_chidren_surname_four").val(engaged_chidren_surname_four);
                                                }
                                                var engaged_chidren_dob_four = obj.engaged_chidren_dob_four;
                                                if (engaged_chidren_dob_four != ''){
                                                    $('#engaged_chidren_dob_four').datepicker('setDate', engaged_chidren_dob_four);
                                                } 

                                                var engaged_chidren_name_five = obj.engaged_chidren_name_five;
                                                if (engaged_chidren_name_five != ''){
                                                    $(".engaged_chidren_name_five").val(engaged_chidren_name_five);
                                                }
                                                var engaged_chidren_surname_five = obj.engaged_chidren_surname_five;
                                                if (engaged_chidren_surname_five != ''){
                                                    $(".engaged_chidren_surname_five").val(engaged_chidren_surname_five);
                                                }
                                                var engaged_chidren_dob_five = obj.engaged_chidren_dob_five;
                                                if (engaged_chidren_dob_five != ''){
                                                    $('#engaged_chidren_dob_five').datepicker('setDate', engaged_chidren_dob_five);
                                                } 

                                                var engaged_chidren_name_six = obj.engaged_chidren_name_six;
                                                if (engaged_chidren_name_six != ''){
                                                    $(".engaged_chidren_name_six").val(engaged_chidren_name_six);
                                                }
                                                var engaged_chidren_surname_six = obj.engaged_chidren_surname_six;
                                                if (engaged_chidren_surname_six != ''){
                                                    $(".engaged_chidren_surname_six").val(engaged_chidren_surname_six);
                                                }
                                                var engaged_chidren_dob_six = obj.engaged_chidren_dob_six;
                                                if (engaged_chidren_dob_six != ''){
                                                    $('#engaged_chidren_dob_six').datepicker('setDate', engaged_chidren_dob_six);
                                                } 

                                            }    
                                            //EngagedChildrenEnd

                                            $(".statusfirstchild").css("display","none");
                                            for ( var i = 0 ; i < 7 ; i++ ) {
                                                var id = ".first_childofchild_"+i;
                                                $(id).each(function() {   
                                                $(".first_childofchild_"+i).css("display","none");
                                                $(".statusfirstchild").children().children().val("");
                                                });
                                             }
    				                        $(".statusmarriedchild").css("display","none");
                                            for ( var i = 0 ; i < 7 ; i++ ) {
                                                var id = ".childofchild_"+i;
                                                $(id).each(function() {   
                                                $(".childofchild_"+i).css("display","none");
                                                $(".statusmarriedchild").children().children().val("");
                                                });
                                             }
    				                    }

    				                    if(civil_status == '2'){ 
    				                    	$(".statusmarriedchild").css("display","block");
    				                    	$(".statussinglechild").css("display","none");
                                            for ( var i = 0 ; i < 7 ; i++ ) {
                                                var id = ".engaged_childofchild_"+i;
                                                $(id).each(function() {   
                                                $(".engaged_childofchild_"+i).css("display","none");
                                                 $(".statussinglechild").children().children().val("");
                                                });
                                             }
                                            $(".statusfirstchild").css("display","none");
                                            for ( var i = 0 ; i < 7 ; i++ ) {
                                                var id = ".first_childofchild_"+i;
                                                $(id).each(function() {   
                                                $(".first_childofchild_"+i).css("display","none");
                                                $(".statusfirstchild").children().children().val("");
                                                });
                                             }
    				                    	//alert(civil_status);
                                    		
    				                    	var wedding_anniversary = obj.wedding_anniversary;
                                    		if (wedding_anniversary != ''){
                                            $('#wedding_anniversary , #wedding_anniversary_it').datepicker('setDate', wedding_anniversary);
                                    			
                                    		}

                                    		//alert(obj.partner_name);
    				                        var partner_name = obj.partner_name;
                                    		if (partner_name != ''){
                                    			$("#partner_name").val(partner_name);
                                    		}
                                    		var partner_surname = obj.partner_surname;
                                    		if (partner_surname != ''){
                                    			$("#partner_surname").val(partner_surname);
                                    		}
                                    		var partner_dob = obj.partner_dob;
                                    		if (partner_dob != ''){
                                    			$('#datepickers_it_married').datepicker('setDate', partner_dob);
                                        		$('#datepickers_married').datepicker('setDate', partner_dob);
                                    		}
                                    		var no_of_child = obj.no_of_child;
                                    		if (no_of_child != ''){
                                    			$(".no_of_child").val(no_of_child);
                                    			 var v = no_of_child;            
    							                   for ( var i = v; i >= 0 ; i-- ) {
    							                       var id = ".childofchild_"+i;
    							                       $(id).each(function() {
    							                       $(".childofchild_"+i).css("display","block");
    							                    });
    							                   } 

    							                   var vv = parseInt(no_of_child) + parseInt(1);
    							                   for ( var i = vv ; i < 7 ; i++ ) {
    							                        var id = ".childofchild_"+i;
    							                        $(id).each(function() {   
    							                        $(".childofchild_"+i).css("display","none")
    							                        });
    							                    }

    							               	var chidren_name_one = obj.chidren_name_one;
    	                                		if (chidren_name_one != ''){
    	                                			//alert(chidren_name_one);
    	                                			$(".chidren_name_one").val(chidren_name_one);
    	                                		}
    	                                		var chidren_surname_one = obj.chidren_surname_one;
    	                                		if (chidren_surname_one != ''){
    	                                			$(".chidren_surname_one").val(chidren_surname_one);
    	                                		}
    	                                		var chidren_dob_one = obj.chidren_dob_one;
    	                                		if (chidren_dob_one != ''){
    	                                			$('#chidren_dob_one').datepicker('setDate', chidren_dob_one);
    	                                		}  
    	                                		var chidren_name_two = obj.chidren_name_two;
    	                                		if (chidren_name_two != ''){
    	                                			$(".chidren_name_two").val(chidren_name_two);
    	                                		}
    	                                		var chidren_surname_two = obj.chidren_surname_two;
    	                                		if (chidren_surname_two != ''){
    	                                			$(".chidren_surname_two").val(chidren_surname_two);
    	                                		}
    	                                		var chidren_dob_two = obj.chidren_dob_two;
    	                                		if (chidren_dob_two != ''){
    	                                			$('#chidren_dob_two').datepicker('setDate', chidren_dob_two);
    	                                		}  

    	                                		var chidren_name_three = obj.chidren_name_three;
    	                                		if (chidren_name_three != ''){
    	                                			$(".chidren_name_three").val(chidren_name_three);
    	                                		}
    	                                		var chidren_surname_three = obj.chidren_surname_three;
    	                                		if (chidren_surname_three != ''){
    	                                			$(".chidren_surname_three").val(chidren_surname_three);
    	                                		}
    	                                		var chidren_dob_three = obj.chidren_dob_three;
    	                                		if (chidren_dob_three != ''){
    	                                			$('#chidren_dob_three').datepicker('setDate', chidren_dob_three);
    	                                		}  

    	                                		var chidren_name_four = obj.chidren_name_four;
    	                                		if (chidren_name_four != ''){
    	                                			$(".chidren_name_four").val(chidren_name_four);
    	                                		}
    	                                		var chidren_surname_four = obj.chidren_surname_four;
    	                                		if (chidren_surname_four != ''){
    	                                			$(".chidren_surname_four").val(chidren_surname_four);
    	                                		}
    	                                		var chidren_dob_four = obj.chidren_dob_four;
    	                                		if (chidren_dob_four != ''){
    	                                			$('#chidren_dob_four').datepicker('setDate', chidren_dob_four);
    	                                		} 

    	                                		var chidren_name_five = obj.chidren_name_five;
    	                                		if (chidren_name_five != ''){
    	                                			$(".chidren_name_five").val(chidren_name_five);
    	                                		}
    	                                		var chidren_surname_five = obj.chidren_surname_five;
    	                                		if (chidren_surname_five != ''){
    	                                			$(".chidren_surname_five").val(chidren_surname_five);
    	                                		}
    	                                		var chidren_dob_five = obj.chidren_dob_five;
    	                                		if (chidren_dob_five != ''){
    	                                			$('#chidren_dob_five').datepicker('setDate', chidren_dob_five);
    	                                		} 

    	                                		var chidren_name_six = obj.chidren_name_six;
    	                                		if (chidren_name_six != ''){
    	                                			$(".chidren_name_six").val(chidren_name_six);
    	                                		}
    	                                		var chidren_surname_six = obj.chidren_surname_six;
    	                                		if (chidren_surname_six != ''){
    	                                			$(".chidren_surname_six").val(chidren_surname_six);
    	                                		}
    	                                		var chidren_dob_six = obj.chidren_dob_six;
    	                                		if (chidren_dob_six != ''){
    	                                			$('#chidren_dob_six').datepicker('setDate', chidren_dob_six);
    	                                		} 

                                    		}
                                    		
    				                    }
    				                    

                                    	
                                        $('#civil_status').val(civil_status);
                                        //$('#civil_status').attr("disabled", true);
                                    }else{
                                         $('#civil_status').val('0');
                                    }
                                    var degree_education = obj.degree_education;
                                    if (degree_education != '' || degree_education != null) {
                                        $('#degree_education').val(degree_education);
                                       // $('#degree_education').attr("disabled", true);
                                    }else{
                                         $('#degree_education').val('0');
                                    }
                                    var profession = obj.profession;
                                    if (profession != '' || profession != null) {
                                        $('#profession').val(profession);
                                        //$('#profession').attr("disabled", true);
                                    }
                                    else{
                                         $('#profession').val('0');
                                    }
                                    var buying_opportunity = obj.buying_opportunity;
                                    if (buying_opportunity != '' || buying_opportunity != null) {
                                        $('#buying_opportunity').val(buying_opportunity);
                                    }else{
                                         $('#buying_opportunity').val('0');
                                    }
                                    var reason_purchase = obj.reason_purchase;
                                    if (reason_purchase != '' || reason_purchase != null) {
                                        $('#reason_purchase').val(reason_purchase);
                                    }
                                    else{
                                         $('#reason_purchase').val('0');
                                    }
                                    var reason_choice = obj.reason_choice;
                                    if (reason_choice != '' || reason_choice != null) {
                                        $('#reason_choice').val(reason_choice);
                                    }
                                    else{
                                         $('#reason_choice').val('0');
                                    }
                                    var came_to_know = obj.came_to_know;
                                    if (came_to_know != '' || came_to_know != null) {
                                        $('#came_to_know').val(came_to_know);
                                    }
                                    else{
                                         $('#came_to_know').val('0');
                                    }

                                    var seller_name = obj.seller_name;
                                    if (seller_name != '') {
                                        $('#seller_name-2').val(seller_name);
                                    }

                                    var status = obj.status;
                                    if (status != '') {
                                        $('#status').val(status);
                                    }
                                    var created_at = obj.created_at;
                                    if (created_at != '') {
                                        $('#created_at').val(created_at);
                                    }
                                    var updated_at = obj.updated_at;
                                    if (updated_at != '') {
                                        $('#updated_at').val(updated_at);
                                    }
                                    var customer_id = obj.customer_id;
                                    if (customer_id != '') {
                                        $('#customer_id').val(customer_id);
                                    }
                                    var customer_c_id = obj.customer_c_id;
                                    if (customer_c_id != '') {
                                        $('#customer_ids').val(customer_c_id);
                                    }
                                    var customer_c_group_id = obj.customer_c_group_id;
                                    if (customer_c_group_id != '') {
                                        $('#customer_group_id').val(customer_c_group_id);
                                    }
                                });
                                $(".loading-mask").css('display', 'none');
                            });
                        });
                    });
                });

