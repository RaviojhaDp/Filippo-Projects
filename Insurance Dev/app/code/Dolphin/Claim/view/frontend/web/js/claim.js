require([
        'jquery',
        'mage/url',
        'Magento_Ui/js/modal/modal',
        'Magento_Customer/js/customer-data',
        'mage/validation',
        'mage/calendar',
        'steps',
        'jquery/ui',

    ], function ($,urlBuilder,modal,customerData) {

        $("#autosuggest_close").on('click', function () {
                    $(".show").hide();
                });


         if ($('html')[0].lang == "it") {
                    var title =  $.mage.__('Fai clic alla fine della pagina su Firma')
                    var text = $.mage.__('Salva documento')
                    var nextext = $.mage.__('PROCEDI')
               }else{
                     var title = $.mage.__('Click at the end of the page on Signature')
                    var text =  $.mage.__('Save Document')
                    var nextext = $.mage.__('PROCEED')
                }

        var dataForm = $('#claim');
        var ignore = null;
        dataForm.mage('validation', {
            ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden'
        }).find('input:text').attr('autocomplete', 'off');

        var form = $("#claim").show();
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            labels:
                    {
                        next: nextext,
                    },
            onStepChanging: function (event, currentIndex, newIndex)
            {

                if (currentIndex === 0) {
                    $('.autofill-contain').hide();
                }
                if (currentIndex === 1) {
                    $('.autofill-contain').hide();
                    $('.pro_select #state option:first-child').attr("selected", "selected");

                }
                 var move = false;
                if (currentIndex === 2) {
                    $('.autofill-contain').hide();
                    var validate = dataForm.validation('isValid');
                            if (validate) {
                                //move = false;
                                 var param = $("#claim").serializeArray();
                               // var param = $("#claim").serialize();

                                 var form_data = new FormData();
                                form_data.append('complaint', $('input[type=file]')[0].files[0]);
                                //form_data.append('damiani_spa', $('input[type=file]')[0].files[0]);
                                 $.each(param,function(key,input){
                                    form_data.append(input.name,input.value);
                                });


                                $.ajax({
                                    type: 'POST',
                                    url: urlBuilder.build('claim/index/claim'),
                                    data: form_data,
                                    contentType: false,
                                    processData: false,
                                    async: false,
                                    cache: false,
                                    timeout: 30000,

                                    success: function (data) {
                                        customerData.reload('messages');
                                        if (data.status === 'true')
                                        {
                                           // move = true;
                                           //console.log(data);
                                            $('#certificato_code_sign').val(data.claim_id);
                                            $('#created_at_sign').val(data.created_at);

                                            return true;
                                        }

                                    }
                                });
                            }


                }
                if (currentIndex == 3) {
                    $(".autofill-contain").hide();
                    //$("#claim").submit();
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
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex)
            {
                alert("Submitted!");
                $("#example-advanced-form").submit();
            }
        });
        $('#claim_datepickers').datepicker({
            prevText: '&#x3c;zur??ck', prevStatus: '',
            prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
            nextText: 'Vor&#x3e;', nextStatus: '',
            nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
            dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            showMonthAfterYear: false,
            showOn: "both",
            buttonImage: "https://certificato.luxmadein.it/media/ICON-CALEDAR-NEW.svg",
            buttonImageOnly: true,
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            required: true,
            disabled:true
        });
        $('#claim_purchase_date_datepickers').datepicker({
            prevText: '&#x3c;zur??ck', prevStatus: '',
            prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
            nextText: 'Vor&#x3e;', nextStatus: '',
            nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
            dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            showMonthAfterYear: false,
            showOn: "button",
            buttonImage: "https://certificato.luxmadein.it/media/ICON-CALEDAR-NEW.svg",
            buttonImageOnly: true,
            dateFormat: 'dd/mm/yy',
            changeMonth: false,
            changeYear: false,
            required: false,
            disabled:true
        });
        $('#left_date_date_datepickers_it').datepicker({
            closeText: "Chiudi",
            prevText: "&#x3C;Prec",
            nextText: "Succ&#x3E;",
            currentText: "Oggi",
            monthNames: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno",
                "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
            monthNamesShort: ["Gen", "Feb", "Mar", "Apr", "Mag", "Giu",
                "Lug", "Ago", "Set", "Ott", "Nov", "Dic"],
            dayNames: ["Domenica", "Luned??", "Marted??", "Mercoled??", "Gioved??", "Venerd??", "Sabato"],
            dayNamesShort: ["Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab"],
            dayNamesMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
            weekHeader: "Sm",
            dateFormat: "dd/mm/yy",
            showOn: "both",
            buttonImage: "https://certificato.luxmadein.it/media/ICON-CALEDAR-NEW.svg",
            buttonImageOnly: true,
            firstDay: 1,
            changeMonth: true,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: "",
            changeYear: true,
            yearRange: "-40:+0",
        });

        $('#left_date_date_datepickers').datepicker({
            prevText: '&#x3c;zur??ck', prevStatus: '',
            prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
            nextText: 'Vor&#x3e;', nextStatus: '',
            nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
            dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            showMonthAfterYear: false,
            showOn: "both",
            buttonImage: "https://certificato.luxmadein.it/media/ICON-CALEDAR-NEW.svg",
            buttonImageOnly: true,
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-40:+0",
        });
        $('#claim_termination_it').datepicker({
            closeText: "Chiudi",
            prevText: "&#x3C;Prec",
            nextText: "Succ&#x3E;",
            currentText: "Oggi",
            monthNames: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno",
                "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
            monthNamesShort: ["Gen", "Feb", "Mar", "Apr", "Mag", "Giu",
                "Lug", "Ago", "Set", "Ott", "Nov", "Dic"],
            dayNames: ["Domenica", "Luned??", "Marted??", "Mercoled??", "Gioved??", "Venerd??", "Sabato"],
            dayNamesShort: ["Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab"],
            dayNamesMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
            weekHeader: "Sm",
            dateFormat: "dd/mm/yy",
            showOn: "both",
            buttonImage: "https://certificato.luxmadein.it/media/ICON-CALEDAR-NEW.svg",
            buttonImageOnly: true,
            firstDay: 1,
            changeMonth: true,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: "",
            changeYear: true,
            yearRange: "-40:+0",
        }
        );
        $('#claim_termination').datepicker({
            prevText: '&#x3c;zur??ck', prevStatus: '',
            prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
            nextText: 'Vor&#x3e;', nextStatus: '',
            nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
            dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            showMonthAfterYear: false,
            showOn: "both",
            buttonImage: "https://certificato.luxmadein.it/media/ICON-CALEDAR-NEW.svg",
            buttonImageOnly: true,
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            required: true
        });
        $(".show").hide();
        $('#claim').css('display', 'none');
        $('#claim #name-2,#claim #name-2,#claim #surname-2,#claim #address-2,#claim #zipcode-2,#claim #country,#claim #city-22,#claim #states,#claim #phone-2,#claim #fiscal-code-2,#claim #claim_datepickers,#claim #gender,#claim #email_address,#claim #model,#claim #equpiment-2,#claim #serial_number-2,#claim #name_boutique_retailer-2,#claim #add_boutique_retailer-2').attr("readonly", true);
        $('#claim #country').attr("disabled", true);
        $(".show").hide();
        $('#customername-2').on('keyup paste change', function () {

            var key = $(this).val();
            var AjaxUrl = urlBuilder.build('claim/index/autosuggest');
            $.ajax({
                showLoader: true,
                url: AjaxUrl,
                data: key,
                type: "POST"
            }).done(function (data) {
                $(".show").show();
                $("#autosuggest").html(data);
                $(".result").click(function (e) {
                    e.preventDefault();
                    AjaxUrl2 = urlBuilder.build('claim/index/autoload');
                    var text = $(this).text();
                    var val = $(this).attr("data-value");
                    $("#customername-2").val(text);
                    $(".show").hide();
                    $.ajax({
                        showLoader: true,
                        url: AjaxUrl2,
                        data: {param: val},
                        type: "POST"
                    }).done(function (data) {
                        $("#active-insurance_rb").html(data);

                    });
                    $(".loading-mask").css('display', 'none');
                });
            });
        });

        $('#claim').css('display', 'none');
        $('.autofill-contain').removeClass('activeon');

        $('#active-insurance_rb').on('change', function () {

            var val = $(this).val();
            var AjaxUrl2 = urlBuilder.build('claim/index/autoloadclaim');
            $.ajax({
                showLoader: true,
                url: AjaxUrl2,
                data: {param: val},
                type: "POST"
            }).done(function (data) {

                $('#claim').css('display', 'block');
                $('.autofill-contain').addClass('activeon');

                $('#claim #name-2,#claim #name-2,#claim #surname-2,#claim #address-2,#claim #zipcode-2,#claim #country,#claim #city-22,#claim #states,#claim #phone-2,#claim #fiscal-code-2,#claim #claim_datepickers,#claim #gender,#claim #email_address,#claim #model,#claim #equpiment-2,#claim #serial_number-2,#claim #name_boutique_retailer-2,#claim #add_boutique_retailer-2').attr("readonly", true);

                //var obj = JSON.parse(data);
                var obj = JSON.parse(JSON.stringify(data));
                $('#faber_post').val(JSON.stringify(data));
                var certificato_id = obj.certificato_id;
                var customer_group_id = obj.customer_group_id;
                if (customer_group_id != '') {
                    $('#customer_group_id').val(customer_group_id);
                }
                var name = obj.name;
                if (name != '') {
                    $('#name-2').val(name);
                }
                var certificato_id = obj.certificato_id;

                if (certificato_id != '') {
                    $('#certificato_id').val(certificato_id);

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
                    console.log(city);
                    $('#city-22').val(city);
                }
                var region = obj.region;
                console.log(region);
                if (region != '') {
                    $('#state').val(region);
                    $('#states').val(region);
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
                if (fiscal_code != '') {
                    $('#fiscal-code-2').val(fiscal_code);
                }
                var dob = obj.dob;
                if (dob != '') {
                    $('#claim_datepickers').datepicker('setDate', dob);
                }
                var gender = obj.sex;
                if (gender != '') {
                    $('#gender').val(gender);
                    $('#gender').attr("selected","selected");
                }
                var model = obj.model;
                //alert(model);
                if (model != '') {
                    $('#model').val(model);
                }
                var equpiment = obj.equpiment;
                //alert(equipment);
                if (equpiment != '') {
                    $('#equpiment-2').val(equpiment);
                }
                var stone_code = obj.stone_code;
                //alert(equipment);
                if (stone_code != '') {
                    $('#stonecode-2').val(stone_code);
                }
                
                var model = obj.model;
                if (model != '') {
                    $('#model-2').val(model);
                }
                var serial_number = obj.serial_number;
                if (serial_number != '') {
                    $('#serial_number-2').val(serial_number);
                }
                var name_boutique_retailer = obj.name_boutique_retailer;
                var name_boutique_retailer_dup = obj.name_boutique_retailer_dup;
                if (name_boutique_retailer != '') {
                    $('#name_boutique_retailer-2').val(name_boutique_retailer_dup);
                    $('#name_boutique_retailer-2_dup').val(name_boutique_retailer);
                }
                var add_boutique_retailer = obj.add_boutique_retailer;
                if (add_boutique_retailer != '') {
                    $('#add_boutique_retailer-2').val(add_boutique_retailer);
                }
                var seller_name = obj.seller_name;
                if (seller_name != '') {
                    $('#seller_name-2').val(seller_name);
                }
                var purchase_date = obj.purchase_date

                if (purchase_date != '') {
                    $('#claim_purchase_date_datepickers').datepicker('setDate', purchase_date);
                }
                var filetoupload = obj.filetoupload;

                if (filetoupload != "undefined") {
                    $('#filetouploadInput').val("");
                }

                var general_conditions = obj.general_conditions;
                if (general_conditions != '') {
                    $('#general_conditions').val(general_conditions);
                }
                var privacy = obj.privacy;
                if (general_conditions != '') {
                    $('#general_conditions').val(general_conditions);
                }
                var marketing = obj.marketing;
                if (marketing != '') {
                    $('#marketing').val(marketing);
                }
                var profiling = obj.profiling;
                if (profiling != '') {
                    $('#profiling').val(profiling);
                }
                var cession = obj.cession;
                if (cession != '') {
                    $('#cession').val(cession);
                }
                var status = obj.status;
                if (status != '') {
                    $('#status').val(status);
                }
                var created_at = obj.created_at;
                if (created_at != '') {
                    $('#created_at').val(created_at);
                }
                var country_id = obj.country_id;
                if (country_id != '') {
                    $('#country').val(country_id);
                }
                var updated_at = obj.updated_at;
                if (updated_at != '') {
                    $('#updated_at').val(updated_at);
                }
                var customer_id = obj.customer_id;
                if (customer_id != '') {
                    $('#customer_id').val(customer_id);
                }
            });
            $(".loading-mask").css('display', 'none');

        });


    $(document).ready(function() {
        $("#click-mes").on('click', function () {
            $(".loading-mask").css('display', 'block');
        });

            if($(".pro_select #state option:first").val() == ''){
                $(".pro_select #state option:first").attr('disabled','disabled');
                }
                    $('#filetoupload').change(function () {

                        var a = $('#filetoupload').val().toString().split('\\');
                        $('#filetouploadInput').val(a[a.length -1]);
                    });
                    $('#filetoupload_spa').change(function () {

                        var a = $('#filetoupload_spa').val().toString().split('\\');
                        $('#filetouploadInput_spa').val(a[a.length -1]);
                    });
                });

        });

