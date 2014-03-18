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

function mcc_map_shortcode($atts)
{
    extract( shortcode_atts(array(
        'name'  => false,
        'title' => true
    ), $atts, 'mcc-map'));

    if (false === $name) {
        return;
    }

    $args = array(
        'name'          => $name,
        'post_type'     => 'mcc-route',
        'post_status'   => 'publish',
        'numberposts'   => 1
    );

    $route = get_posts($args);

    if ($route) {
        $route = $route[0];
    } else {
        return;
    }

    ?>

    <div class="map-widget">
        <?php if ((bool) $title === true): ?>
        <h2><?php echo $route->post_title; ?></h2>
        <?php endif; ?>
        <div class="mcc-map-embed map" id="mcc-map-<?php echo $route->ID; ?>" data-map="">
            <?php if (get_field('kml_url', $route->ID)): ?>
                <div class="kml-layer" data-url="<?php the_field('kml_url', $route->ID); ?>"></div>
            <?php endif; ?>

            <?php
                while (has_sub_field('markers', $route->ID)):
                    $label = get_sub_field('label');
                    $lat   = get_sub_field('lat');
                    $lng   = get_sub_field('lng');
                    ?>
                    <div class="marker"
                         data-marker=""
                         data-lng="<?php echo $lng; ?>"
                         data-lat="<?php echo $lat; ?>"
                         data-title="<?php echo $label; ?>">
                    </div>
                <?php endwhile; ?>
        </div>
    </div>
    <?php
}
add_shortcode('mcc-map', 'mcc_map_shortcode');

function mcc_map_tabs_shortcode($atts)
{
    extract( shortcode_atts(array(
        'names' => false
    ), $atts, 'mcc-map'));

    if (false === $names || 0 === strlen($names)) {
        return;
    }

    $routeNames = explode(',', $names);
    $routes = array();

    foreach ($routeNames as $name) {
        $args = array(
            'name'          => $name,
            'post_type'     => 'mcc-route',
            'post_status'   => 'publish',
            'numberposts'   => 1
        );

        $route = get_posts($args);

        if ($route) {
            $routes[] = $route[0];
        }
    }

    if ($routes):

    ?>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="dropdown active">
                <a href="#" id="routeSelect" class="dropdown-toggle" data-toggle="dropdown">
                    Route Select <b class="caret"></b>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="routeSelect">
                    <?php
                        $first = true;
                        foreach ($routes as $route):
                            ?>
                            <li <?php echo ($first) ? 'class="active"' : ''; ?>>
                                <a href="#tab<?php echo $route->ID; ?>" data-toggle="tab">
                                    <?php echo $route->post_title; ?>
                                </a>
                            </li>
                            <?php
                            $first = false;
                        endforeach;
                    ?>
                </ul>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <?php
                $first = true;
                foreach ($routes as $route):
                    ?>
                    <div class="tab-pane fade <?php echo ($first) ? 'active in' : ''; ?>" id="tab<?php echo $route->ID; ?>">
                        <?php do_shortcode('[mcc-map name=' . $route->post_name . ' title=0]'); ?>
                    </div>
                    <?php
                    $first = false;
                endforeach;
            ?>
        </div>
    <?php
    endif;
}
add_shortcode('mcc-map-tabs', 'mcc_map_tabs_shortcode');