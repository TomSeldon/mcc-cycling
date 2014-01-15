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
    // Lets alter the label to use a Glyphicon
    // Add icon
    $regex      = '/gfield_label/';
    $replace    = "gfield_label input-group-addon glyphicon $icon_class";
    $content    = preg_replace($regex, $replace, $content);

    // Hide label text
    $regex      = '/<label(.+)>(.+)<\/label>/';
    $replace    = '<label$1><span class="sr-only">$2</span></label>';
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
