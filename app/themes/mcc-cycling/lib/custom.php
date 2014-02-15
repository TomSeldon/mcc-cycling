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

        get_template_part('templates/content');

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

    if (is_search()) {
        $subtitle = __('You searched for: <br />', 'mcc') . get_search_query();
    }

    if (is_post_type_archive(array('mcc-accommodation','mcc-activities'))) {
        $count    = wp_count_posts(get_post_type())->publish;
        $subtitle = __($count . ' locations found', 'mcc');
    }

    return $subtitle;
}
add_filter('page_subtitle', 'mcc_set_subtitles');

/**
 * Use custom class for submit button.
 *
 * @param $button
 * @param $form
 * @return string
 */
function form_submit_button($button, $form)
{
    return "<button type='submit' class='redButton' id='gform_submit_button_{$form["id"]}'><span>Submit</span></button>";
}
add_filter("gform_submit_button", "form_submit_button", 10, 2);

function gform_label_replace_glyphicon($content, $icon_class)
{
    // Hide the label (except for screen readers)
//    $regex      = '/gfield_label/';
//    $replace    = 'sr-only';
//    $content    = preg_replace($regex, $replace, $content);

    // Add necessary styles to show icon
    $regex      = '/<div class=[\'"]ginput_container[\'"]>(.+)<\/div>/';
    $replace    = "<div class='ginput_container input-group'>$1<span class='glyphicon $icon_class'></span></div>";
    $content    = preg_replace($regex, $replace, $content);

    return $content;
}

/**
 * Checks for known field CSS classes and replaces the field's
 * label with a Glyphicon. The original label text is retained,
 * but a CSS class is added making it viewable only to screen
 * readers.
 *
 * @param $content
 * @param $field
 * @param $value
 * @param $lead_id
 * @param $form_id
 * @return mixed
 */
function form_glyphicons_name($content, $field, $value, $lead_id, $form_id)
{
    $classes = array(
        'name-icon'     => 'glyphicon-user',
        'email-icon'    => 'glyphicon-envelope',
        'phone-icon'    => 'glyphicon-phone',
        'message-icon'  => 'glyphicon-pencil'
    );

    foreach ($classes as $class => $icon) {
        if (false !== strpos($field['cssClass'], $class)) {
            $content = gform_label_replace_glyphicon($content, $icon);
        }
    }

    return $content;
}
add_filter("gform_field_content", "form_glyphicons_name", 10, 5);

/**
 * Adds a short code for generating red buttons.
 *
 * @param $atts
 * @param $content
 * @return string
 */
function shortcode_red_button($atts, $content)
{
    extract(shortcode_atts(array(
        'href'  => '#'
    ), $atts));

    $content = "<a href='$href' class='redButton'>$content</a>";

    return $content;
}
add_shortcode('red-button', 'shortcode_red_button', 10, 2);

/**
 * Registers the Google Maps script using API details from the settings.
 */
function register_gmaps_script()
{
    $api_key   = get_redux_field('gmaps_api_key');
    $gmaps_url = "https://maps.googleapis.com/maps/api/js?key=$api_key&sensor=false&libraries=geometry";

    wp_register_script('gmaps', $gmaps_url, array(), false, true);

    wp_enqueue_script('gmaps');
}
add_action('wp_enqueue_scripts', 'register_gmaps_script');

/**
 * Checks if a Strava Route has been assigned to the specified post.
 * Uses current post if no post ID specified.
 *
 * @param null $postID
 * @return bool
 */
function get_strava_route($postID=null)
{
    global $post;

    if ($postID === null) {
        $postID = $post->ID;
    }

    $routePost = get_field('route', $postID);

    // Check if a route field was set
    if (false !== $routePost) {
        // Is it a route post type?
        if (isset($routePost->ID) && 'route' === get_post_type($routePost->ID)) {
            return $routePost;
        }
    }

    return false;
}

/**
 * Checks if a 'route' field is set.
 *
 * If it is, and it's a Route post, then we pass it back to
 * the Strava plugin.
 *
 * @param $route
 * @return bool|WP_Post
 */
function set_strava_handle_route($route)
{
    $routePost = get_strava_route();

    if (false !== $routePost) {
        $route = $routePost;
    }

    return $route;
}

/**
 * If this is the home page, localize the Strava plugin scripts
 * on the main Roots script.
 *
 * @param $handle
 * @return string
 */
function set_strava_handle_home($handle)
{
    if (false !== get_strava_route()) {
        $handle = 'roots_scripts';
    }

    return $handle;
}
add_filter('StravaRoutes/Scripts/Localize/Route', 'set_strava_handle_route');
add_filter('StravaRoutes/Scripts/Localize/Handle', 'set_strava_handle_home');

/**
 * Add some rules to control when sidebar is shown.
 *
 * @param $sidebar
 * @return bool
 */
function mcc_filter_sidebar($sidebar)
{
    if (!is_front_page() && is_page_template('default')) {
        $sidebar = true;
    } else {
        $sidebar = false;
    }

    return $sidebar;
}
add_filter('roots_display_sidebar', 'mcc_filter_sidebar');

/**
 * Adds a post type for locations.
 */
function add_post_type_locations()
{
    $accom_labels = array(
        'add_new_item'  => 'Add New Accommodation',
        'edit_item'     => 'Edit Accommodation',
        'search_items'  => 'Search Accommodation'
    );

    $activity_labels = array(
        'add_new_item'  => 'Add New Activity',
        'edit_item'     => 'Edit Activity',
        'search_items'  => 'Search Activities'
    );

    $activity_args = array(
        'label'         => 'Activities',
        'labels'        => $activity_labels,
        'public'        => true,
        'menu_icon'     => 'dashicons-admin-site',
        'supports'      => array('title','editor','thumbnail'),
        'has_archive'   => true,
        'with_front'    => true,
        'rewrite'       => array('slug' => 'activities')
    );

    $accom_args = array(
        'label'         => 'Accommodation',
        'labels'        => $accom_labels,
        'public'        => true,
        'menu_icon'     => 'dashicons-admin-site',
        'supports'      => array('title','editor','thumbnail'),
        'has_archive'   => true,
        'with_front'    => true,
        'rewrite'       => array('slug' => 'places')
    );

    register_post_type('mcc-accommodation', $accom_args);
    register_post_type('mcc-activities', $activity_args);
}
add_action('init', 'add_post_type_locations');

/**
 * Alter the main query for locations archive.
 *
 * @param $query
 */
function mcc_locations_posts_per_page($query)
{
    if ( is_admin() || ! $query->is_main_query() )
        return;

    if ( is_post_type_archive( 'location' ) ) {
        // Display 50 posts for a custom post type called 'movie'
        $query->set( 'posts_per_page', 15 );
        $query->set( 'order', 'asc' );
        $query->set( 'orderby', 'title' );
        return;
    }
}
add_action('pre_get_posts', 'mcc_locations_posts_per_page');

/**
 * Return array of all locations for a given post type.
 *
 * @param $post_type
 *
 * @return array
 */
function mcc_get_locations($post_type)
{
    $post_args = array(
        'post_type'     => $post_type,
        'numberposts'   => -1
    );

    $posts = get_posts($post_args);

    $locations = array();

    foreach ($posts as $post) {
        $location = get_field('location', $post->ID);

        if (false !== $location) {
            $location['post'] = $post;

            $locations[] = $location;
        }
    }

    return $locations;
}