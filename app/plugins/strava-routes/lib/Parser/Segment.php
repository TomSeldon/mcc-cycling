<?php
/**
 * Segment.php
 * Author: Tom Seldon
 * Created: 19/01/2014
 */

namespace TS\StravaRoutes\Parser;

use Guzzle\Http\Client;
use Guzzle\Http\EntityBody;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

class Segment {

    private static $client = "https://www.strava.com";

    /**
     * Return a single segment via the Strava API.
     *
     * @param $id - The segment ID. *Not* the post ID of the segment in WordPress.
     * @return \stdClass
     */
    public static function fetchSegment($id)
    {
        global $StravaRedux;

        $client     = new Client(self::$client);
        $request    = $client->get('/api/v3/segments/' . $id);

        $query      = $request->getQuery();
        $query->set('access_token', $StravaRedux['strava_access_token']);

        $response   = $request->send();
        $body       = $response->getBody(true);

        return json_decode($body);
    }

    /**
     * Returns an array of segments.
     *
     * @param $id - The ID of the segment post.
     * @return array - An array of post metadata for the requested segment.
     */
    public static function parseSegmentPostForAPI($id)
    {
        $segment    = get_post($id);
        $response   = false;

        if (false !== $segment) {
            $response = array(
                'id'        => get_post_meta($id, 'id', true),
                'name'      => $segment->post_title,
                'polyline'  => get_post_meta($id, 'polyline', true),
                'start_latitude'    => get_post_meta($id, 'start_latitude', true),
                'start_longitude'   => get_post_meta($id, 'start_longitude', true),
                'end_latitude'      => get_post_meta($id, 'end_latitude', true),
                'end_longitude'     => get_post_meta($id, 'end_longitude', true),
                'distance'          => get_post_meta($id, 'distance', true)
            );
        }

        return $response;
    }

    /**
     * When dealing with many segment post IDs, this will return an array
     * of data for each one.
     *
     * @param $ids
     * @return array
     */
    public static function parseSegmentPostsForAPI($ids)
    {
        $results = array();

        foreach ($ids as $id) {
            $result = self::parseSegmentPostForAPI($id);

            if (false !== $result) {
                $results[] = $result;
            }
        }

        return $results;
    }
} 