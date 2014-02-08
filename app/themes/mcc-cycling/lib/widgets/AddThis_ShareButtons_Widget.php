<?php
/**
 * AddThis_ShareButtons_Widgets.php
 * Author: Tom Seldon
 * Created: 08/02/2014
 */

/**
 * Class AddThis_ShareButtons_Widgets
 */
class AddThis_ShareButtons_Widget extends WP_Widget {

    /**
     * Initialise the widget.
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'widget_mcc_addthis_share',
            'description' => __('Use this widget to add share buttons (powered by AddThis).', 'mcc')
        );

        $this->WP_Widget('widget_mcc_addthis_share', __('AddThis: Share buttons', 'mcc'), $widget_ops);
        $this->alt_option_name = 'widget_mcc_addthis_share';
    }

    /**
     * Display the share buttons.
     */
    public function widget()
    {
        get_template_part('templates/addthis','share');
    }
}
