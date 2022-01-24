define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    return function (config) {

        setAccordian();

        function setAccordian() {
            $(".faq-accordion").accordion({
                collapsible: true,
                heightStyle: "content",
                active: '',
                animate: 500,
                beforeActivate: function(event, ui) {
             // The accordion believes a panel is being opened
            if (ui.newHeader[0]) {
                var currHeader  = ui.newHeader;
                var currContent = currHeader.next('.ui-accordion-content');
             // The accordion believes a panel is being closed
            } else {
                var currHeader  = ui.oldHeader;
                var currContent = currHeader.next('.ui-accordion-content');
            }
             // Since we've changed the default behavior, this detects the actual status
            var isPanelSelected = currHeader.attr('aria-selected') == 'true';
            
             // Toggle the panel's header
            currHeader.toggleClass('ui-corner-all',isPanelSelected).toggleClass('accordion-header-active ui-state-active ui-corner-top',!isPanelSelected).attr('aria-selected',((!isPanelSelected).toString()));
            
            // Toggle the panel's icon
            currHeader.children('.ui-icon').toggleClass('ui-icon-triangle-1-e',isPanelSelected).toggleClass('ui-icon-triangle-1-s',!isPanelSelected);
            currHeader.children('.ui-accordion .ui-state-default:after')
             // Toggle the panel's content
            currContent.toggleClass('accordion-content-active',!isPanelSelected)    
            if (isPanelSelected) { currContent.slideUp(); }  else { currContent.slideDown(); }

            return false; // Cancels the default action
              }
          });
        }

        function scrollToGroup(data) {
            var target = $(data.getAttribute('href'));
            if( target.length ) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top
                }, 1000);
            }
        }

        $('.group-link').on('click', function(event) {
            if (config.pageType == 'scroll') {
                scrollToGroup(this);
            } else {
                var self = this;
                event.preventDefault();
                var groupId = $(this).attr('groupid');
                var groupUrl = config.ajax_url + 'faq/index/ajax';
                $.ajax({
                    url: groupUrl,
                    type: 'POST',
                    dataType: 'json',
                    showLoader: true,
                    data: {
                        groupId: groupId
                    },
                    complete: function(response) {
                        $('#faq-content').html(response.responseJSON.faq);
                        setAccordian();
                        scrollToGroup(self);
                    }
                });
            }
        });
    }
});
