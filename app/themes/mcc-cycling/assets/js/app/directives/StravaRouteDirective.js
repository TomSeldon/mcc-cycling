'use strict';

(function(){
    var stravaRouteDirective = function()
    {
        return {
            restrict:   'A',
            controller: 'StravaMapController',
            transclude: true,
            scope:      {
                routeId: '='
            },
            link: function(scope, element, attrs) {
                scope.elementId = attrs.id;
            }
        };
    };

    angular.module('MCCApp').directive('stravaRoute', [stravaRouteDirective]);
}());