(function(){
    'use strict';

    var Settings = function()
    {
        /**
         * Set some default options for map objects.
         *
         * @constructor
         */
         this.MapOptions = function(options)
        {
            options = (typeof options === 'object' ? options : {});

            for (var option in options) {
                if (options.hasOwnProperty(option)) {
                    this[option] = options[option];
                }
            }
        };

        /**
         * Create array of map style objects with some default values.
         *
         * @constructor
         */
        this.MapStyles = function(styles)
        {
            this._styles = (typeof styles === 'undefined' ? [] : styles);

            /**
             * Add new style declarations.
             *
             * @param styles
             */
            this.addStyles = function(styles) {
                for (var i=0; i<styles.length; i++) {
                    this._styles.push(styles[i]);
                }
            };

            /**
             * Add a single style object.
             *
             * @param style
             */
            this.addStyle = function(style) {
                this._styles.push(style);
            };

            /**
             * Return list of style objects.
             *
             * @returns {Array}
             */
            this.getStyles = function() {
                return this._styles;
            };
        };
    };

    angular.module('MCCApp').service('Settings', [Settings]);
}());