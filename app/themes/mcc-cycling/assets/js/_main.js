// Modified http://paulirish.com/2009/markup-based-unobtrusive-comprehensive-dom-ready-execution/
// Only fires on body class (working off strictly WordPress body_class)

var MCCSite = {
    // All pages
    common: {
        init: function() {
            if (isSafari()) {
                jQuery('body').addClass('safari');
            }

            if (isChrome()) {
                jQuery('body').addClass('chrome');
            }

            jQuery(".navbar-side-collapse").mCustomScrollbar({
                scrollInertia:          500,
                updateOnContentResize:  true
            });

            var $topMenu      = jQuery(".top-menu");
            var $backToTopBtn = jQuery("#back-to-top-container");
            var windowHeight  = jQuery(window).height();
            var belowFold     = false;

            $backToTopBtn.on('click', function(){
                jQuery("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
            });

            /**
             * Shows or hides the back to top button depending on
             * whether the user is below the fold.
             */
            function toggleBackToTop()
            {
                if (true === belowFold) {
                    if (!$backToTopBtn.is(':visible')) {
                        $backToTopBtn.fadeIn();
                    }
                } else {
                    if ($backToTopBtn.is(':visible')) {
                        $backToTopBtn.fadeOut();
                    }
                }
            }

            /**
             * Toggles the 'sticky' top menu depending
             * on whether the user is below the fold.
             */
            function toggleStickyMenu()
            {
                if (true === belowFold) {
                    if (!$topMenu.hasClass('sticky')) {
                        $topMenu.addClass('sticky fadeIn');
                    }
                } else {
                    if ($topMenu.hasClass('sticky')) {
                        $topMenu.removeClass('sticky fadeIn');
                    }
                }
            }

            /**
             * Checks whether the user is below the fold.
             */
            function calculateBelowFold()
            {
                windowHeight = jQuery(window).height();
                var offset   = $backToTopBtn.offset().top;

                belowFold = (offset > windowHeight);

                return belowFold;
            }

            jQuery(window).scroll(function(){
                calculateBelowFold();
                toggleBackToTop();
                toggleStickyMenu();
            });
        },
        finalize: function() { }
    },
    // Home page
    home: {
        init: function() {
            // Force full height sections
            var $sections = jQuery('.main > .section, #top-header');

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