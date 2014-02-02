<?php

/**
 * Shortcode to display location thumbnail.
 */
function mcc_location_shortcode($atts)
{
    if (array_key_exists('id', $atts)) {
        $id = $atts['id'];
    } else {
        return;
    }

    $location = get_post($id);

    if (false === $location || 'location' !== $location->post_type || !has_post_thumbnail($location->ID)) {
        return;
    }

    $thumbnail = get_the_post_thumbnail($location->ID);
    $permalink = get_permalink($location->ID);

    ?>

    <div class="col-sm-4 location">
        <a href="<?php echo $permalink; ?>">
            <?php
                    echo $thumbnail;
            ?>
            <div class="label"><?php echo $location->post_title; ?></div>
        </a>
    </div>
<?php
}
add_shortcode('mcc-location', 'mcc_location_shortcode');