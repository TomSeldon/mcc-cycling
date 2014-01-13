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

/**
 * Change the post menu to article
 */
function mcc_change_post_menu_text() {
    global $menu;
    global $submenu;

    // Change menu item
    $menu[5][0] = 'News';

    // Change post submenu
    $submenu['edit.php'][5][0] = 'News';
    $submenu['edit.php'][10][0] = 'Add News Article';
    $submenu['edit.php'][16][0] = 'News Tags';
}
add_action('admin_menu', 'mcc_change_post_menu_text');

/**
 * Change the post type labels
 */
function change_post_type_labels() {
    global $wp_post_types;

    // Get the post labels
    $postLabels = $wp_post_types['post']->labels;
    $postLabels->name = 'Articles';
    $postLabels->singular_name = 'News';
    $postLabels->add_new = 'Add News Article';
    $postLabels->add_new_item = 'Add News Article';
    $postLabels->edit_item = 'Edit Article';
    $postLabels->new_item = 'News';
    $postLabels->view_item = 'View Articles';
    $postLabels->search_items = 'Search Articles';
    $postLabels->not_found = 'No Articles found';
    $postLabels->not_found_in_trash = 'No Articles found in Trash';
}
add_action( 'init', 'change_post_type_labels' );

/**
 * Allows us to conditionally set the page sub-title.
 *
 * @param $subtitle
 * @return string|void
 */
function mcc_set_subtitles($subtitle)
{
    if (is_404() || !have_posts()) {
      $subtitle = __('Sorry, but the page you were trying to view does not exist.', 'mcc');
    }

    return $subtitle;
}
add_filter('page_subtitle', 'mcc_set_subtitles');
