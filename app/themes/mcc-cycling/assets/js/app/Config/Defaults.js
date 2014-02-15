(function(){
    'use strict';

    var Defaults = function(Settings)
    {
        var defaults = this;

        /**
         * The default styles to use for the map object.
         *
         * @type {Settings.MapStyles}
         * @constructor
         */
        this.MapStyles = function()
        {
            return new Settings.MapStyles([
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
            ]);
        };

        /**
         * The default options for the map object.
         *
         * @type {Settings.MapOptions}
         * @constructor
         */
        this.MapOptions = function()
        {
            var options = new Settings.MapOptions({
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
            });

            options.styles = defaults.MapStyles().getStyles();

            return options;
        };
    };

    angular.module('MCCApp').service('Defaults', ['Settings', Defaults]);
}());