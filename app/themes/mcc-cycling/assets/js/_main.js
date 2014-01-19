// Modified http://paulirish.com/2009/markup-based-unobtrusive-comprehensive-dom-ready-execution/
// Only fires on body class (working off strictly WordPress body_class)

var MCCSite = {
    // All pages
    common: {
        init: function() {
            // JS here
        },
        finalize: function() { }
    },
    // Home page
    home: {
        init: function() {
            var styles = [
                {
                    stylers: [
                        { hue: "#e1e0db" },
                        { saturation: -75 },
                        { gamma: 1.2 }
                    ]
                },{
                    featureType: "road",
                    elementType: "geometry",
                    stylers: [
                        { lightness: 100 },
                        { visibility: "simplified" }
                    ]
                },{
                    featureType: "road",
                    elementType: "labels",
                    stylers: [
                        { visibility: "simplified" }
                    ]
                }
            ];

            var mapOptions = {
                center: new google.maps.LatLng(-34.397, 150.644),
                zoom:   12,
                disableDefaultUI: true,
                draggable: false,
                zoomControl: false,
                scrollwheel: false,
                disableDoubleClickZoom: true,
                keyboardShortcuts: false,
                panControl: false,
                rotateControl: false,
                styles: styles
            };

            var map = new google.maps.Map(document.getElementById("map-routes-bg"),     mapOptions);
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
