<?php
/**
 * Plugin.php
 * Author: Tom Seldon
 * Created: 19/01/2014
 */

namespace TS\StravaRoutes;

use TS\StravaRoutes;
use TS\StravaRoutes\PostType;

class Plugin {

    public function __construct()
    {
        $this->registerPostTypes();

        add_action('init', array(&$this, 'addReduxSettings'));

        //$segments = RouteParser::getRouteSegments('http://www.strava.com/routes/120415');
    }

    /**
     * Register any post types used by the plugin.
     */
    public static function registerPostTypes()
    {
        $CPTRoute   = new PostType\Route();
        $CPTSegment = new PostType\Segment();
    }

    /**
     * Instantiates the Redux settings object.
     */
    public static function addReduxSettings()
    {
        $GLOBALS[Redux\Config::$ReduxName . '_ReduxFramework'] = Redux\Config::buildFramework();
    }
}