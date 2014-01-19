<?php
/**
 * Segment.php
 * Author: Tom Seldon
 * Created: 19/01/2014
 */

namespace TS\StravaRoutes\PostType;


class Segment {

    public static $postType = 'segment';

    public function __construct()
    {
        add_action('init', array(&$this, 'registerPostType'));
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
            'label'                 => __('Segments', 'stravaroutes'),
            'labels'                => self::getLabels(),
            'description'           => __('Strava Segments', 'stravaroutes'),
            'public'                => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'menu_icon'             => 'dashicons-flag',
            'supports'              => array('title','custom-fields')
        );

        return $args;
    }

    /**
     * Define the labels to use for the post type.
     *
     * @return array
     */
    private static function getLabels()
    {
        $labels = array(
            'name'                  => __('Segments', 'stravaroutes'),
            'singular_name'         => __('Segment', 'stravaroutes'),
            'menu_name'             => __('Strava Segments', 'stravaroutes'),
            'add_new_item'          => __('Add New Segment', 'stravaroutes'),
            'edit_item'             => __('Edit Segment', 'stravaroutes'),
            'new_item'              => __('New Segment', 'stravaroutes'),
            'view_item'             => __('View Segment', 'stravaroutes'),
            'search_items'          => __('Search Segments', 'stravaroutes'),
            'not_found'             => __('No segments found', 'stravaroutes'),
            'not_found_in_trash'    => __('No segments found in trash', 'stravaroutes'),
        );

        return $labels;
    }

    /**
     * Gets a segment post by it's segment ID, *not* the post ID.
     *
     * @param $segmentID
     * @returns \WP_Post
     */
    public static function getSegmentById($segmentID)
    {
        $post_args = array(
            'post_type'     => self::$postType,
            'numberposts'   => 1,
            'meta_key'      => 'id',
            'meta_value'    => $segmentID
        );

        $post = get_posts($post_args);

        foreach ($post as $segment) {
            $post = $segment;
            break;
        }

        return $post;
    }

    /**
     * When dealing with many segment IDs, this function will return an
     * array of the corresponding segment posts.
     *
     * @param $segmentIDs
     * @return array
     */
    public static function getSegmentsBySegmentIds($segmentIDs)
    {
        $segments = array();

        foreach ($segmentIDs as $id) {
            $segment = self::getSegmentById($id);

            if (false !== $segment) {
                $segments[] = $segment;
            }
        }

        return $segments;
    }
}