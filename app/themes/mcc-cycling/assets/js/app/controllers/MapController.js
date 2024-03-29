'use strict';

(function(){
    var MapController = function($scope, MapService)
    {
        var fitMap = false;

        $scope.map              = null;
        $scope.mapEl            = null;
        $scope.markers          = [];
        $scope.kmlLayers        = [];
        $scope.markerCluster    = null;
        $scope.infowindow       = null;
        $scope.mapOptions       = {};

        // Set elements to use to calculate margin for map bounds
        $scope.leftMarginEl = null;

        $scope.locationsMapSettings = {
            lockPosition: false,
            draggable:   true,
            zoomControl: true,
            scrollwheel: true,
            disableDoubleClickZoom: false,
            keyboardShortcuts: true,
            panControl: false,
            rotateControl: false
        };

        $scope.embedMapOptions = {
            lockPosition: false,
            zoom: 11,
            disableDefaultUI: true,
            draggable: true,
            zoomControl: true,
            scrollwheel: true,
            disableDoubleClickZoom: false,
            keyboardShortcuts: false,
            panControl: false,
            rotateControl: false
        };

        $scope.fullscreenMapOptions = {
            lockPosition: true,
            zoom: 11,
            disableDefaultUI: true,
            draggable: false,
            zoomControl: false,
            scrollwheel: false,
            disableDoubleClickZoom: true,
            keyboardShortcuts: false,
            panControl: false,
            rotateControl: false
        };

        function init()
        {
            $scope.map = MapService.createMap($scope.mapEl, $scope.mapOptions);

            $scope.infowindow = new google.maps.InfoWindow({
                content: 'pre'
            });

            $scope.$watch('markers', function(){
                $scope.markerCluster = MapService.createMarkerCluster($scope.map, $scope.markers);

                MapService.fitToMarkers($scope.map, $scope.markers);
            });

            $scope.$watch('kmlUrls', function(){
                $scope.kmlLayers = [];

                for (var i=0; i < $scope.kmlUrls.length; i++) {
                    var url      = $scope.kmlUrls[i];
                    var ctaLayer = new google.maps.KmlLayer({url: url});

                    ctaLayer.setMap($scope.map);

                    $scope.kmlLayers.push(ctaLayer);
                }
            });
        }

        $scope.$on('map.marker.click', function(event, marker){
            if (marker.content && jQuery.trim(marker.content).length) {
                $scope.infowindow.setContent(marker.content);
                $scope.infowindow.setPosition(marker.getPosition());
                $scope.infowindow.open($scope.map);
            }
            else if (marker.title && jQuery.trim(marker.title).length) {
                $scope.infowindow.setContent('<strong>' + marker.title + '</strong>');
                $scope.infowindow.setPosition(marker.getPosition());
                $scope.infowindow.open($scope.map);
            }
        });

        $scope.$on('map.bounds_changed', function() {
            if ($scope.map.lockPosition === true || fitMap === false) {
                MapService.fitToMarkers($scope.map, $scope.markers);

                fitMap = true;
            }
        });

        angular.element(window).on('resize', function(){
            if ($scope.map.lockPosition === true) {
                google.maps.event.trigger($scope.map,'resize');
                MapService.fitToMarkers($scope.map, $scope.markers);
            }
        });

        $scope.$on('map.projection_changed', function(event, map) {
            MapService.fitToMarkers($scope.map, $scope.markers);

            fitMap = true;
        });

        $scope.$on('shown.bs.tab', function() {
            MapService.fitToMarkers($scope.map, $scope.markers);

            if ($scope.markers.length === 0) {
                google.maps.event.trigger($scope.map,'resize');
                MapService.fitToKml($scope.map, $scope.kmlLayers);
            }

            fitMap = true;
        });

        $scope.$watch('mapEl', function(mapEl){
            $scope.mapEl = mapEl;

            init();
        });
    };

    angular.module('MCCApp').controller('MapController', ['$scope', 'MapService', MapController]);
}());