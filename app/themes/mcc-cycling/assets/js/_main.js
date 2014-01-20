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
                },
                {
                    featureType: "road",
                    elementType: "geometry",
                    stylers: [
                        { lightness: 100 },
                        { visibility: "simplified" }
                    ]
                },
                {
                    featureType: "road",
                    elementType: "labels",
                    stylers: [
                        { visibility: "off" }
                    ]
                },
                {
                    elementType: "labels",
                    stylers: [
                        { visibility: "simplified" }
                    ]
                }
            ];

            var monmouthshire = new google.maps.LatLng(51.667778, -2.881389);

            var mapOptions = {
                center: monmouthshire,
                zoom:   11,
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

            var polylines = [];
            var bounds    = new google.maps.LatLngBounds();
            var routes    = StravaRoutes.routes;
            var route;

            // Convert segments to polylines
            for (var i=0; i<routes.length; i++) {
                route = routes[i];

                for (var j=0; j<route.segments.length; j++) {
                    var segment         = route.segments[j];
                    var encodedPolyline = segment.polyline;
                    var decodedPolyline = google.maps.geometry.encoding.decodePath(encodedPolyline);

                    polylines.push(decodedPolyline);
                }
            }

            var map = new google.maps.Map(document.getElementById("map-routes-bg"), mapOptions);

            // Add polylines to map
            for (var k=0; k<polylines.length; k++) {
                var path = polylines[k];

                var polyOptions = {
                    strokeColor: '#000000',
                    strokeOpacity: 1.0,
                    strokeWeight: 5,
                    path: path
                };

                // Extend bounds
                for (var l=0; l<path.length; l++) {
                    bounds.extend(path[l]);
                }

                var polyline = new google.maps.Polyline(polyOptions);

                polyline.setMap(map);
            }

            google.maps.event.addListener(map, 'bounds_changed', function() {
                map.fitBounds(bounds);
            });

            map.fitBounds(bounds);
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

console.log(StravaRoutes);
