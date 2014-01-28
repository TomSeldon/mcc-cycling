'use strict';

(function(){
    var MapDirective = function()
    {
        return {
            restrict:   'A',
            controller: 'MapController',
            transclude: false,
            replace:    false,
            scope:      {
            },
            link: function(scope, element) {
                // Create map element
                var $mapEl = document.createElement('div');
                    $mapEl.setAttribute('id', 'location-map');

                angular.element(element).append($mapEl);

                scope.mapEl = $mapEl;

                // Get markers
                var markers    = [];
                var $markersEl = element.find('.marker');

                $markersEl.each(function(index, $markerEl){
                    var marker = {};

                    marker.lng = angular.element($markerEl).data('lng');
                    marker.lat = angular.element($markerEl).data('lat');

                    markers.push(marker);
                });

                scope.markers = markers;
            }
        };
    };

    angular.module('MCCApp').directive('map', [MapDirective]);
}());