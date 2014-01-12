<?php
/**
 * Custom functions
 */

// Hide the admin toolbar
add_filter('show_admin_bar', '__return_false');

/**
 * Returns a redux field.
 *
 * @param $field
 * @return mixed|bool
 */
function get_redux_field($field)
{
    global $mcc_redux;

    return (isset($mcc_redux[$field]) && !empty($mcc_redux[$field]) ? $mcc_redux[$field] : false);
}

/**
 * Echos out the value of the specified Redux field.
 *
 * @param $field
 */
function the_redux_field($field)
{
    echo get_redux_field($field);
}

/**
 * Displays the single latest post.
 */
function mcc_show_latest_post()
{
    global $post;

    $args = array(
        'posts_per_page'    => 1,
        'post_type'         => 'post',
        'post_status'       => 'publish'
    );

    $posts = get_posts($args);

    foreach ($posts as $post) {
        setup_postdata($post);

        get_template_part('templates/archive-post');

        wp_reset_postdata();
        break;
    }
}
