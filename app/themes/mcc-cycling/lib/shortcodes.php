<?php

/**
 * Shortcode to display location thumbnail.
 */
function mcc_location_shortcode($atts)
{
    extract( shortcode_atts(array(
        'id'                => false,
        'show_container'    => 1
    ), $atts, 'mcc-location'));

    if (false === $id) {
        return;
    }

    $location       = get_post($id);
    $show_container = ($show_container >= 1);

    if (false === $location || 'location' !== $location->post_type || !has_post_thumbnail($location->ID)) {
        return;
    }

    $thumbnail = get_the_post_thumbnail($location->ID);
    $permalink = get_permalink($location->ID);

    ?>

    <?php if (true === $show_container): ?>
    <div class="locations row">
        <div class="col-xs-12">
    <?php endif; ?>

            <div class="location cta">
                <a href="<?php echo $permalink; ?>">
                    <?php
                            echo $thumbnail;
                    ?>
                    <div class="label"><?php echo $location->post_title; ?></div>
                </a>
            </div>

    <?php if (true === $show_container): ?>
        </div>
    </div>
    <?php endif; ?>

<?php
}
add_shortcode('mcc-location', 'mcc_location_shortcode');