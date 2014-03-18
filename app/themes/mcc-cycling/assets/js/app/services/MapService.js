/*jshint bitwise: false*/

(function(){
    'use strict';

    var MapService = function($rootScope, Defaults)
    {
        /**
         * Create a Google Map on the specified element.
         *
         * @param element
         * @param options
         */
        this.createMap = function(element, options)
        {
            var baseOptions = new Defaults.MapOptions();

            if (options) {
                for (var option in baseOptions) {
                    if (baseOptions.hasOwnProperty(option) && !options.hasOwnProperty(option)) {
                        options[option] = baseOptions[option];
                    }
                }
            } else {
                options = baseOptions;
            }

            var map = new google.maps.Map(element, options);

            google.maps.event.addListener(map, 'bounds_changed', function() {
                $rootScope.$broadcast('map.bounds_changed', map);
            });

            google.maps.event.addListener(map, 'projection_changed', function() {
                $rootScope.$broadcast('map.projection_changed', map);
            });

            return map;
        };

        /**
         * Adds markers to map.
         *
         * @param map
         * @param markers
         *
         * @retrun bool|{MarkerClusterer}
         */
        this.createMarkerCluster = function(map, markers)
        {
            if (!map) {
                return false;
            }

            markers = (typeof markers === 'undefined' ? [] : markers);

            var markerCluster = new MarkerClusterer(map);

            var markerClick = function(event) {
                $rootScope.$broadcast('map.marker.click', this, event);
            };

            for (var i=0; i<markers.length; i++) {
                var marker = markers[i];

                google.maps.event.addListener(marker, 'click', markerClick);

                markerCluster.addMarker(marker, true);
            }

            return markerCluster;
        };

        /**
         * Centers map on kml layer.
         *
         * @param map
         * @param kmlLayers
         */
        this.fitToKml = function(map, kmlLayers)
        {
            if (typeof kmlLayers === 'undefined') {
                return;
            }

            for (var i=0; i < kmlLayers.length; i++) {
                map.fitBounds(kmlLayers[i].getDefaultViewport());
            }
        };

        /**
         * Centers the map so all markers can be seen.
         *
         * @param {google.maps.Map} map
         * @param {array} markers
         * @param {int} padding
         */
        this.fitToMarkers = function(map, markers, padding)
        {
            markers = (typeof markers ===  'undefined' ? [] : markers);
            padding = (typeof padding ===  'undefined' ? 40 : padding);

            var changed = false;

            if (markers.length === 0) {
                return;
            }

            var bounds  = new google.maps.LatLngBounds();

            for (var i=0; i<markers.length; i++) {
                var marker = markers[i];

                var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );

                bounds.extend(latlng);
            }

            var zoom = this.getZoomByBounds(map, bounds, padding);

            if (zoom !== map.getZoom()) {
                map.setZoom(zoom);
                changed = true;
            }

            if (map.getCenter() !== bounds.getCenter()) {
                map.setCenter(bounds.getCenter());
                changed = true;
            }

            if (changed === true) {
                google.maps.event.trigger(map,'resize');
            }
        };

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
        this.getZoomByBounds = function(map, bounds, padding)
        {
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
                    return Math.min(14,zoom);
                }
            }

            return 0;
        };
    };

    angular.module('MCCApp').service('MapService', ['$rootScope', 'Defaults', MapService]);
}());