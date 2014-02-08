<?php

require_once dirname(__FILE__) . '/widgets/Roots_Vcard_Widget.php';
require_once dirname(__FILE__) . '/widgets/AddThis_ShareButtons_Widget.php';

/**
 * Register sidebars and widgets
 */
function roots_widgets_init() {
  // Sidebars
    register_sidebar(array(
        'name'          => __('Main Menu', 'roots'),
        'id'            => 'sidebar-main-menu',
        'before_widget' => '<section class="widget %1$s %2$s"><div class="widget-inner">',
        'after_widget'  => '</div></section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Primary', 'roots'),
        'id'            => 'sidebar-primary',
        'before_widget' => '<section class="widget %1$s %2$s"><div class="widget-inner">',
        'after_widget'  => '</div></section>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => __('Footer', 'roots'),
        'id'            => 'sidebar-footer',
        'before_widget' => '<section class="widget %1$s %2$s"><div class="widget-inner">',
        'after_widget'  => '</div></section>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>',
    ));

  // Widgets
  register_widget('Roots_Vcard_Widget');
  register_widget('AddThis_ShareButtons_Widget');
}
add_action('widgets_init', 'roots_widgets_init');

/**
 * Enabled HTML to be used where it would otherwise be stripped out.
 *
 * @param $safe_text
 * @param $text
 * @return mixed
 */
function mcc_allow_html($safe_text, $text)
{
    return $text;
}

/**
 * Checks for widgets whose titles should be replaced with icons.
 *
 * @param $instance
 * @param $widget
 * @param $args
 * @return mixed
 */
function mcc_widget_title_icons($instance, $widget, $args)
{
    // Add the icon if this is a Twitter widget
    if ('twitter_timeline' === $widget->id_base)
        add_filter('widget_title', 'mcc_twitter_timeline_icon');

    // Add the icon if this is a Facebook widget
    if ('facebook-likebox' === $widget->id_base) {
        add_filter('widget_title', 'mcc_facebook_feed_icon');
        add_filter('esc_html', 'mcc_allow_html', 10, 2);
    }

    return $instance;
}
add_filter('widget_display_callback', 'mcc_widget_title_icons', 10, 3);

/**
 * Adds twitter icon to widget title and hides the title text.
 *
 * @param $title
 * @return string
 */
function mcc_twitter_timeline_icon($title) {
    $title =  '<i class="fa fa-twitter"></i><span class="title">' . $title . '</span>';

    remove_filter('widget_title', 'mcc_twitter_timeline_icon');

    return $title;
}

/**
 * Adds twitter icon to widget title and hides the title text.
 *
 * @param $title
 * @return string
 */
function mcc_facebook_feed_icon($title) {
    $title =  '<i class="fa fa-facebook"></i><span class="title">' . $title . '</span>';

    remove_filter('widget_title', 'mcc_facebook_feed_icon');

    return $title;
}

