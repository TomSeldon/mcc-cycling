<?php
/**
 * Route.php
 * Author: Tom Seldon
 * Created: 19/01/2014
 */

namespace TS\StravaRoutes\PostType;

use TS\StravaRoutes\Sync;

class Route {

    public static $postType = 'route';

    public function __construct()
    {
        add_action('init', array(&$this, 'registerPostType'));
        add_action('save_post',  array(__NAMESPACE__ . '\\' . self::$postType, 'savePost'));
    }

    public static function savePost($postID)
    {
        if (self::$postType !== $_POST['post_type']) {
            return;
        }

        // Check if we should update the segments
        if (true === get_field('auto_fetch_segments', $postID)) {
            remove_action('save_post', array(__NAMESPACE__ . '\\' . self::$postType, 'savePost'));
            Sync::syncRouteSegments($postID);
        }
    }

    /**
     * Register the post type.
     */
    public static function registerPostType()
    {
        \register_post_type(self::$postType, self::getPostTypeArgs());
    }

    /**
     * Return the post type arguments.
     *
     * @return array
     */
    private static function getPostTypeArgs()
    {
        $args = array(
            'label'                 => __('Routes', 'stravaroutes'),
            'labels'                => self::getLabels(),
            'description'           => __('Strava Routes', 'stravaroutes'),
            'public'                => true,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'menu_icon'             => 'dashicons-location-alt',
            'supports'              => array('title','custom-fields')
        );

        return $args;
    }

    private static function getLabels()
    {
        $labels = array(
            'name'                  => __('Routes', 'stravaroutes'),
            'singular_name'         => __('Route', 'stravaroutes'),
            'menu_name'             => __('Strava Routes', 'stravaroutes'),
            'add_new_item'          => __('Add New Route', 'stravaroutes'),
            'edit_item'             => __('Edit Route', 'stravaroutes'),
            'new_item'              => __('New Route', 'stravaroutes'),
            'view_item'             => __('View Route', 'stravaroutes'),
            'search_items'          => __('Search Routes', 'stravaroutes'),
            'not_found'             => __('No routes found', 'stravaroutes'),
            'not_found_in_trash'    => __('No routes found in trash', 'stravaroutes'),
        );

        return $labels;
    }
} 