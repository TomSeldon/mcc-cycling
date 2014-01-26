// Modified http://paulirish.com/2009/markup-based-unobtrusive-comprehensive-dom-ready-execution/
// Only fires on body class (working off strictly WordPress body_class)

var MCCSite = {
    // All pages
    common: {
        init: function() {
            var $backToTopBtn = jQuery("#back-to-top");
            var windowHeight = jQuery(window).height();

            $backToTopBtn.on('click', function(){
                jQuery("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
            });

            function toggleBackToTop()
            {
                windowHeight = jQuery(window).height();
                var offset   = $backToTopBtn.offset().top;

                if (offset > windowHeight) {
                    if (!$backToTopBtn.is(':visible')) {
                        $backToTopBtn.fadeIn();
                    }
                } else {
                    if ($backToTopBtn.is(':visible')) {
                        $backToTopBtn.fadeOut();
                    }
                }
            }

            jQuery(window).scroll(function(){
               toggleBackToTop();
            });
        },
        finalize: function() { }
    },
    // Home page
    home: {
        init: function() {
            // Force full height sections
            var $sections = jQuery('.main > .section');

            function fitSections()
            {
                var height = jQuery(window).height();

                jQuery.each($sections, function(i, $section){
                  jQuery($section).css('min-height', height);
                });
            }

            jQuery(window).resize(function(){
                fitSections();
            });

            fitSections();
        }
    },
    // About page
    about: {
        init: function() {
            // JS here
        }
    }
};

var UTIL = {
    fire: function(func, funcname, args) {
        var namespace = MCCSite;
        funcname = (funcname === undefined) ? 'init' : funcname;
        if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
            namespace[func][funcname](args);
        }
    },
    loadEvents: function() {

        UTIL.fire('common');

        $.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
            UTIL.fire(classnm);
        });

        UTIL.fire('common', 'finalize');
    }
};

$(document).ready(UTIL.loadEvents);