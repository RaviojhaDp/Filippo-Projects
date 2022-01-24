define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function (jQuery , modal) {
    'use strict';

    return function (config,modal) {
       // alert(jQuery('body').attr('class'));
       var sPageURL = window.location.search.substring(1),
                        sURLVariables = sPageURL.split('&'),
                        sParameterName,
                        i;

                    for (i = 0; i < sURLVariables.length; i++) {
                        sParameterName = sURLVariables[i].split('=');

                        if (sParameterName[0] === "popup") {
                             sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                             var popupvar = sParameterName[1];
                             alert(popupvar);
                             if(popupvar == "true"){
                                var optionhome = {
                                                    type: 'popup',
                                                    responsive: true,
                                                    innerScroll: true,
                                                    title: "",
                                                    buttons: [{
                                                            text: "",
                                                            class: '',
                                                            click: function () {
                                                                this.closeModal();
                                                                
                                                                  }
                                                              }]
                                                         };
                                             var popup = modal(optionhome, jQuery('#popup-mpdalhome'));
                                             jQuery("#popup-mpdalhome").modal(optionhome).modal("openModal");
                                                 jQuery("#popup-mpdalhome").html('<div class="equipment"><p>Gentile Cliente,</p><p> le informazioni inserite per la sottoscrizione del Assicurazione, come richiesto dalla normativa vigente per la gestione e il trattamento dei dati personali, non sono state salvate. La invitiamo a contattare il Customer Service dedicato (ecustomerservice@damiani.come) per avere maggiori informazioni.</p><br><p>Cordiali Saluti</p><br><p>Gruppo Damiani</p></div>');
                                        
                             }
                        }
                    }

    }
});