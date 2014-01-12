<?php
/**
 * redux.php
 * Author: Tom Seldon
 * Created: 12/01/2014
 */

// Setup Redux
function mcc_redux_init()
{
    global $ReduxFramework;

    if (!class_exists('ReduxFramework')) {
        return;
    }

    // Set Redux args
    $args = array(
        'dev_mode'              => false,
        'opt_name'              => 'mcc_redux',
        'system_info'           => false,
        'display_name'          => 'testing 123',
        'show_import_export'    => true,
        'menu_title'            => __('Site Options', 'mcc'),
        'page_title'            => __('Site Options', 'mcc'),
        'page_slug'             => 'mcc-options',
        'page_cap'              => 'manage_options',
        'page_type'             => 'submenu',
        'allow_sub_menu'        => false
    );

    $sections = array();

    $sections[] = array(
        'icon'      => 'el-icon-screen',
        'title'     => __('Background images', 'mcc'),
        'desc'      => __('<p class="description">Upload and select background images for the various sections.</p>', 'mcc'),
        'fields'    => array(
            array(
                'id'        =>'bg_header',
                'type'      => 'media',
                'title'     => __('Header background image', 'mcc'),
                'preview'   => true
            )
        )
    );

    $ReduxFramework = new ReduxFramework($sections, $args);
}
add_action('init', 'mcc_redux_init');