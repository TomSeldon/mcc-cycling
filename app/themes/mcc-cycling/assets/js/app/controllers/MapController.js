/*jshint bitwise: false*/

'use strict';

(function(){
    var MapController = function($scope)
    {
        $scope.map          = null;
        $scope.markers      = [];
        $scope.infowindow   = null;

        // Set elements to use to calculate margin for map bounds
        $scope.leftMarginEl = null;

        $scope.options      = {};
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

        $scope.options.map.styles = [
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
                    { visibility: "on" }
                ]
            },
            {
                elementType: "labels",
                stylers: [
                    { visibility: "on" }
                ]
            }
        ];

        /**
         * Create a Google Map on the specified element.
         *
         * @param element
         */
        function createMap(element) {
            $scope.map =  new google.maps.Map(element, $scope.options.map);

            $scope.infowindow = new google.maps.InfoWindow({
                content: 'pre'
            });

            google.maps.event.addListener($scope.map, 'bounds_changed', function() {
                $scope.$broadcast('map.bounds_changed', $scope.map);
            });
        }

        /**
         * Adds markers to map.
         *
         * @param map
         * @param markers
         */
        function updateMarkers(map, markers) {
            if (!map || !markers) {
                return;
            }

            for (var i=0; i<markers.length; i++) {
                var marker = markers[i];

                marker.setMap(map);
            }
        }

        /**
         * Centers the map so all markers can be seen.
         *
         * @param {google.maps.Map} map
         */
        function centerMap(map) {
            var bounds  = new google.maps.LatLngBounds();
            var markers = $scope.markers;

            for (var i=0; i<markers.length; i++) {
                var marker = markers[i];

                var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );

                bounds.extend(latlng);
            }

            var padding = null;

            if ($scope.leftMarginEl) {
                padding = $scope.leftMarginEl.width() / 4;
            }

            var zoom    = getZoomByBounds(map, bounds, padding);

            map.setZoom(zoom);
            map.setCenter(bounds.getCenter());
        }

        /**
         * Returns the zoom level at which the given rectangular region fits in the map view.
         * The zoom level is computed for the currently selected map type.
         *
         * @param {google.maps.Map} map
         * @param {google.maps.LatLngBounds} bounds
         * @param {int} padding
         *
         * @return {Number} zoom level
         */
        function getZoomByBounds (map, bounds, padding) {

            if ('undefined' === typeof map.mapTypes.get(map.getMapTypeId())) {
                return 0;
            }

            var MAX_ZOOM = map.mapTypes.get( map.getMapTypeId() ).maxZoom || 21 ;
            var MIN_ZOOM = map.mapTypes.get( map.getMapTypeId() ).minZoom || 0 ;

            var ne= map.getProjection().fromLatLngToPoint( bounds.getNorthEast() );
            var sw= map.getProjection().fromLatLngToPoint( bounds.getSouthWest() );

            var worldCoordWidth = Math.abs(ne.x-sw.x);
            var worldCoordHeight = Math.abs(ne.y-sw.y);

            //Fit padding in pixels
            var FIT_PAD = padding || 40;

            for( var zoom = MAX_ZOOM; zoom >= MIN_ZOOM; --zoom ){
                if( worldCoordWidth*(1<<zoom)+2*FIT_PAD < $(map.getDiv()).width() &&  worldCoordHeight*(1<<zoom)+2*FIT_PAD < $(map.getDiv()).height() ) {
                    return zoom;
                }
            }

            return 0;
        }

        $scope.$on('map.bounds_changed', function(event, map) {
           centerMap(map);
        });

        $scope.$watch('mapEl', function(mapEl){
            createMap(mapEl);
        });

        $scope.$watch('markers', function(){
            updateMarkers($scope.map, $scope.markers);
            centerMap($scope.map);
        });
    };

    angular.module('MCCApp').controller('MapController', ['$scope', MapController]);
}());