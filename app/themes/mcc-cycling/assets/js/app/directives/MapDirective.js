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

                // Set config object
                var configObjName = angular.element(element).data('options');

                scope.mapOptions = (typeof scope[configObjName] === 'undefined' ? {} : scope[configObjName]);

                // Set the element to use for padding
                var leftMarginElId = angular.element(element).data('left-margin-el');

                if (leftMarginElId) {
                    scope.leftMarginEl = angular.element(document.getElementById(leftMarginElId));
                }

                // Get markers
                var markers    = [];
                var $markersEl = element.find('.marker');

                $markersEl.each(function(index, $markerEl){
                    var lng     = angular.element($markerEl).data('lng');
                    var lat     = angular.element($markerEl).data('lat');
                    var title   = angular.element($markerEl).data('title');
                    var content = angular.element($markerEl).html();

                    var latlng = new google.maps.LatLng(lat, lng);

                    markers.push(new google.maps.Marker({
                        position:   latlng,
                        title:      title,
                        content:    content,
                        animation:  google.maps.Animation.DROP
                    }));
                });

                scope.markers = markers;
            }
        };
    };

    angular.module('MCCApp').directive('map', [MapDirective]);
}());