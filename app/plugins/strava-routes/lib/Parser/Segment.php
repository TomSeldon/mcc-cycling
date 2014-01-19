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

} 