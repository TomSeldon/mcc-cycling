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

        function updateMarkers(markers) {
            if (!$scope.map) {
                return;
            }


        }

        $scope.$watch('mapEl', function(mapEl){
            $scope.map =  new google.maps.Map(mapEl, $scope.options.map);
        });

        $scope.$watch('markers', function(markers){
            updateMarkers(markers);
        });
    };

    angular.module('MCCApp').controller('MapController', ['$scope', MapController]);
}());