<?php
/**
 * Routes.php
 * Author: Tom Seldon
 * Created: 19/01/2014
 */

namespace TS\StravaRoutes\Parser;

class Routes {

    /**
     * Returns an array of segment objects for a given route.
     *
     * This is highly dependent on the current syntax of the Strava website.
     * Until they open up their API to allow access to Routes, this is the only
     * feasible, automated way of doing this.
     *
     * @param $url
     * @return array
     */
    public static function getRouteSegments($url)
    {
        $segments = array();

        $html = \file_get_html($url);

        foreach ($html->find('script') as $script) {
            // Search for route segment object literal in page source.
            $regex = '/.routeSegments\((.+)\)( )*.routeAthlete/';

            preg_match($regex, $script, $matches);

            if (count($matches) && isset($matches[1])) {
                $segments = json_decode($matches[1]);
            }
        }

        if ($segments === null) {
            $segments = array();
        }

        return $segments;
    }

}
