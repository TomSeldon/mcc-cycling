'use strict';

(function(){

    var StravaMapController = function($scope, SegmentService)
    {
        $scope.map          = null;
        $scope.routes       = [];
        $scope.route        = null;
        $scope.locations    = {};
        $scope.options      = {};
        $scope.infowindow   = null;

        $scope.options.map  = {
            zoom:   11,
            disableDefaultUI: true,
            draggable: false,
            zoomControl: false,
            scrollwheel: false,
            disableDoubleClickZoom: true,
            keyboardShortcuts: false,
            panControl: false,
            rotateControl: false
        };

        $scope.options.styles = {};
        $scope.options.styles.map = [
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

        $scope.options.styles.segment = {
            strokeColor: '#444',
            hoverStrokeColor: '#aaa',
            strokeOpacity: 1.0,
            strokeWeight: 5
        };

        function init()
        {
            if (typeof StravaRoutes !== 'undefined' && StravaRoutes.hasOwnProperty('routes')) {
                $scope.routes = StravaRoutes.routes;
            }

            addLocation('monmouthshire', new google.maps.LatLng(51.667778, -2.881389));

            $scope.options.map.center = $scope.locations.monmouthshire;
            $scope.options.map.styles = $scope.options.styles.map;

            $scope.infowindow =  new google.maps.InfoWindow({
                content: 'pre'
            });
        }

        function createMap(elementId)
        {
            $scope.map =  new google.maps.Map(document.getElementById(elementId), $scope.options.map);

            google.maps.event.addListener($scope.map, 'bounds_changed', function() {
                $scope.$broadcast('map.bounds_changed', $scope.map);
            });

            $scope.$broadcast('map.loaded', $scope.map);
        }

        /**
         * Adds a location that can be used on the map.
         *
         * @param locationName
         * @param latlng
         */
        var addLocation = function(locationName, latlng) {
            $scope.locations[locationName] = latlng;
        };

        $scope.addPolylinesToMap = function()
        {
            var polyMouseOver = function(event) {
                $scope.$broadcast('map.segment.mouseover', this, event);
            };

            var polyMouseOut = function(event) {
                $scope.$broadcast('map.segment.mouseout', this, event);
            };

            var polyClick = function(event) {
                $scope.$broadcast('map.segment.click', this, event);
            };

            if ($scope.route && $scope.map && $scope.route.segments) {
                for (var i=0; i<$scope.route.segments.length; i++) {
                    var segment         = $scope.route.segments[i];
                    var polylineOptions = $scope.options.styles.segment;

                    polylineOptions.path = segment.polyline;

                    var polyline = new google.maps.Polyline(polylineOptions);

                    polyline.data = segment;

                    google.maps.event.addListener(polyline, 'mouseover', polyMouseOver);
                    google.maps.event.addListener(polyline, 'mouseout', polyMouseOut);
                    google.maps.event.addListener(polyline, 'click', polyClick);

                    polyline.setMap($scope.map);
                }
            }
        };

        $scope.$on('map.segment.mouseover', function(event, polyline){
            polyline.setOptions({strokeColor: $scope.options.styles.segment.hoverStrokeColor});
        });

        $scope.$on('map.segment.mouseout', function(event, polyline){
            polyline.setOptions({strokeColor: $scope.options.styles.segment.strokeColor});
        });

        $scope.$on('map.segment.click', function(event, polyline, mapEvent){
            $scope.infowindow.setContent(SegmentService.getContentForSegment(polyline.data));
            $scope.infowindow.setPosition(mapEvent.latLng);
            $scope.infowindow.open($scope.map);
        });

        $scope.fitMapToRoute = function()
        {
            if ($scope.map && $scope.route && $scope.route.segments) {
                var bounds = new google.maps.LatLngBounds();

                for (var i=0; i<$scope.route.segments.length; i++) {
                    var segment  = $scope.route.segments[i];
                    var path     = segment.polyline;

                    // Extend bounds
                    for (var l=0; l<path.length; l++) {
                        bounds.extend(path[l]);
                    }
                }

                $scope.map.fitBounds(bounds);
            }
        };

        /**
         * Set the active route.
         */
        $scope.$watch('routeId', function(routeId){
            for (var i=0; i<$scope.routes.length; i++) {
                var route = $scope.routes[i];

                if (route.postId === routeId) {
                    $scope.route            = route;
                    $scope.route.segments   = SegmentService.segmentsToPolylines(route.segments);
                    break;
                }
            }
        });

        $scope.$on('map.loaded', function(){
            $scope.addPolylinesToMap();
            $scope.fitMapToRoute();
        });

        $scope.$on('map.bounds_changed', function(){
           $scope.fitMapToRoute();
        });

        /**
         * Watch for changed to the element ID.
         * On change, we'll instantiate a map on it and add some event listeners.
         */
        $scope.$watch('elementId', function(elementId){
            createMap(elementId);
        });

        init();
    };

    angular.module('MCCApp').controller('StravaMapController', ['$scope', 'SegmentService', StravaMapController]);
}());

