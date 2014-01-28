'use strict';

(function(){
    var MapController = function($scope)
    {
        $scope.map      = null;
        $scope.markers  = [];

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
         * @param map
         */
        function centerMap(map) {
            var bounds  = new google.maps.LatLngBounds();
            var markers = $scope.markers;

            for (var i=0; i<markers.length; i++) {
                var marker = markers[i];

                var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );

                bounds.extend(latlng);
            }

            map.setZoom(14);
            map.setCenter(bounds.getCenter());
        }

        $scope.$on('map.bounds_changed', function(event, map){
           console.log(map);
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