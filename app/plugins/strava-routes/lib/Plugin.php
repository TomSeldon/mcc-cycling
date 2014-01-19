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
        add_action('wp_enqueue_scripts', array(&$this, 'localizeScripts'));
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

    /**
     * Adds route + segment data into a script.
     * By default, the script handle and route are false.
     *
     * They can be set dynamically using filters, and the resultant object will only
     * be created and attached if these are set to valid values.
     */
    public static function localizeScripts()
    {
        $route  = apply_filters('StravaRoutes/Scripts/Localize/Route', false);
        $handle = apply_filters('StravaRoutes/Scripts/Localize/Handle', false);


        if (false !== $route && false !== $handle) {
            $objectName = apply_filters('StravaRoutes/Scripts/Localize/ObjectName', 'StravaRoutes');

            $data = array(
                'routes'    => array(
                    array(
                        'name'      => get_the_title($route),
                        'segments'  => Parser\Segment::parseSegmentPostsForAPI(get_field('segments', $route))
                    )
                )
            );

            wp_localize_script($handle, $objectName, $data);
        }
    }
}