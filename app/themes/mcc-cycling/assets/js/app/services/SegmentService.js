'use strict';

(function(){

    var SegmentService = function()
    {
        /**
         * Converts a single segment (encoded polyline) to a Polyline object.
         * @param segment
         * @return {*}
         */
        this.segmentToPolyline = function(segment)
        {
            var encodedPolyline = segment.polyline;

            return google.maps.geometry.encoding.decodePath(encodedPolyline);
        };

        /**
         * Loops through array of segments and converts the encoded polyline
         * to a Polyline object.
         *
         * @param segments
         * @return Array
         */
        this.segmentsToPolylines = function(segments)
        {
            for (var i=0; i<segments.length; i++) {
                segments[i].encodedPolyline = segments[i].polyline;
                segments[i].polyline = this.segmentToPolyline(segments[i]);
            }

            return segments;
        };

        this.getContentForSegment = function(segment)
        {
            console.log(segment);
            var content = '<div class="segment-popup">' +
                '<header><h6>' + segment.name + '</h6></header>' +
                '<p class="distance">Distance: ' + segment.distance + '</p>' +
                '<p class="url"><a target="_blank" href="http://www.strava.com/segments/' + segment.id + '">More details...</a></p>' +
                '</div>';

            return content;
        };
    };

    angular.module('MCCApp').service('SegmentService', [SegmentService]);
}());