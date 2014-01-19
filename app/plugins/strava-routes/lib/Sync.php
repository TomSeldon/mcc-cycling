<?php
/**
 * Sync.php
 * Author: Tom Seldon
 * Created: 19/01/2014
 */

namespace TS\StravaRoutes;

use Symfony\Component\Yaml\Tests\ParserTest;
use TS\StravaRoutes\Parser\Routes as RouteParser;
use TS\StravaRoutes\Parser\Segment as SegmentParser;
use TS\StravaRoutes\PostType\Segment;

class Sync {

    /**
     * Updates a routes segments with data from Strava.
     *
     * Basic flow is as follows:
     *
     *  - Clear existing segment associations
     *  - Retrieve route segments from Strava
     *  - Add/update segments
     *  - Save associations
     *
     * @param $postID
     */
    public static function syncRouteSegments($postID)
    {
        $url = get_field('url', $postID);

        // If this is a valid URL, then fetch the segments
        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $newSegments    = RouteParser::getRouteSegments($url);
            $postIds        = self::syncSegments($newSegments);

            // Add the segment IDs to the route post meta
            if (!update_post_meta($postID, 'segments', $postIds)) {
                add_post_meta($postID, 'segments', $postIds, $unique=true);
            }
        }
    }

    /**
     * After retrieving the raw segment data from Strava, we should
     * cross-check them against existing segment posts and update/create
     * as appropriate.
     *
     * This returns an array of the segment post IDs.
     *
     * @param $rawSegments
     * @return array
     */
    public static function syncSegments($rawSegments)
    {
        $postIds = array();

        foreach ($rawSegments as $segment) {
            $segment = get_object_vars($segment);

            // Check if an existing post exists for this segment
            $existingSegment = PostType\Segment::getSegmentById($segment['id']);

            $postId = (empty($existingSegment) ? null : $existingSegment->ID);

            $postIds[] = self::syncSegment($segment, $postId);
        }

        return $postIds;
    }

    /**
     * Create or update a segment post from raw segment data.
     *
     * If a post ID is set, that post will be updated.
     * If not, a new segment will be created.
     *
     * @param $segment
     * @param $postID
     * @return int|\WP_Error
     */
    private static function syncSegment($segment, $postID=null)
    {
        // Retrieve the segment object from Strava
        $segment = SegmentParser::fetchSegment($segment['id']);

        if (!$postID) {
            $postData = array(
                'post_title'    => $segment->name,
                'post_status'   => 'publish',
                'post_type'     => PostType\Segment::$postType
            );

            $postID = wp_insert_post($postData);
        }

        // Add the post meta
        if ($postID) {
            $metaFields = array(
                'id'                => $segment->id,
                'start_latlng'      => $segment->start_latlng,
                'end_latlng'        => $segment->end_latlng,
                'start_latitude'    => $segment->start_latitude,
                'start_longitude'   => $segment->start_longitude,
                'end_latitude'      => $segment->end_latitude,
                'end_longitude'     => $segment->end_longitude,
                'polyline'          => $segment->map->polyline
            );

            foreach ($metaFields as $key => $value) {
                if (!update_post_meta($postID, $key, $value)) {
                    add_post_meta($postID, $key, $value, $unique=true);
                }
            }
        }

        return $postID;
    }
} 