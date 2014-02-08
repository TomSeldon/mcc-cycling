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
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer', 'roots'),
        'id'            => 'sidebar-footer',
        'before_widget' => '<section class="widget %1$s %2$s"><div class="widget-inner">',
        'after_widget'  => '</div></section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ));

  // Widgets
  register_widget('Roots_Vcard_Widget');
  register_widget('AddThis_ShareButtons_Widget');
}
add_action('widgets_init', 'roots_widgets_init');

function mcc_twitter_timeline_check($instance, $widget, $args)
{
    // Add the icon if this is a Twitter widget
    if ('twitter_timeline' === $widget->id_base)
        add_filter('widget_title', 'mcc_twitter_timeline_icon');

    return $instance;
}
add_filter('widget_display_callback', 'mcc_twitter_timeline_check', 10, 3);

/**
 * Adds twitter icon to widget title and hides the title text.
 *
 * @param $title
 * @return string
 */
function mcc_twitter_timeline_icon($title) {
    $title =  '<i class="fa fa-twitter"></i><span class="sr-only">' . $title . '</span>';

    remove_filter('widget_title', 'mcc_twitter_timeline_icon');

    return $title;
}

