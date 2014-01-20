<?php
/**
 * Plugin Name: Strava Routes
 * Plugin URI: http://github.com/TomSeldon/StravaRoutes/
 * Description: Parses Strava routes
 * Author: Tom Seldon
 * Author URI: http://tomseldon.co.uk/
 * Version: 0.0.1
 * strava-routes.php
 * Created: 19/01/2014
 */

namespace TS\StravaRoutes;

require_once dirname(__FILE__) . "/lib/Parser/Routes.php";
require_once dirname(__FILE__) . "/lib/Parser/Segment.php";
require_once dirname(__FILE__) . "/lib/PostType/Route.php";
require_once dirname(__FILE__) . "/lib/PostType/Segment.php";
require_once dirname(__FILE__) . "/lib/Redux/Config.php";
require_once dirname(__FILE__) . "/lib/Plugin.php";
require_once dirname(__FILE__) . "/lib/Sync.php";

// Instantiate the plugin
$StravaPlugin = new Plugin();