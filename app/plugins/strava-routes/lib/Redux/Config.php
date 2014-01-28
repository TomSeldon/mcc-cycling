<?php
/**
 * Config.php
 * Author: Tom Seldon
 * Created: 19/01/2014
 */

namespace TS\StravaRoutes\Redux;


class Config {

    public static $ReduxName = 'StravaRedux';

    /**
     * Return a new Redux settings object.
     */
    public static function buildFramework()
    {
        if (!class_exists('ReduxFramework')) {
            return;
        }

        return new \ReduxFramework(self::getSections(), self::getArgs());
    }

    /**
     * Returns the arguments to use when constructing the Redux object.
     *
     * @return array
     */
    private static function getArgs()
    {
        $args = array(
            'dev_mode'              => false,
            'opt_name'              => self::$ReduxName,
            'system_info'           => false,
            'display_name'          => __('Strava Routes', 'stravaroutes'),
            'show_import_export'    => true,
            'menu_title'            => __('Strava Routes', 'stravaroutes'),
            'page_title'            => __('Strava Routes', 'stravaroutes'),
            'page_slug'             => 'strava-routes',
            'page_cap'              => 'manage_options',
            'page_type'             => 'submenu',
            'allow_sub_menu'        => true
        );

        return $args;
    }

    /**
     * Returns array of Redux settings.
     *
     * @return array
     */
    private static function getSections()
    {
        $sections = array();

        $sections[] = self::getSectionAPI();

        return $sections;
    }

    /**
     * Returns section for API details.
     *
     * @return array
     */
    private static function getSectionAPI()
    {
        $section = array(
            'icon'      => 'el-icon-unlock',
            'title'     => __('API Settings', 'mcc'),
            'desc'      => __('<p class="description">API related settings.</p>', 'stravaroutes'),
            'fields'    => array(
                array(
                    'id'        =>'gmaps_api_key',
                    'type'      => 'text',
                    'title'     => __('Google Maps API key', 'stravaroutes')
                ),
                array(
                    'id'        =>'strava_access_token',
                    'type'      => 'text',
                    'title'     => __('Strava Access Token', 'stravaroutes')
                )
            )
        );

        return $section;
    }
}
