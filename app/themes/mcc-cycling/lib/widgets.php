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

