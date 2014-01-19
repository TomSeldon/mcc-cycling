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

require_once dirname(__FILE__) . "/vendor/autoload.php";

// Instantiate the plugin
$StravaPlugin = new Plugin();