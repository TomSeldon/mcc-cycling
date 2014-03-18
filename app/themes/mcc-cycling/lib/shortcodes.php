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

    $route  = get_posts($args);
    $output = '';

    if ($route) {
        $route = $route[0];
    } else {
        return $output;
    }

    $output .= '<div class="map-widget">';

        if ((bool) $title === true) {
            $output .= "<h2>$route->post_title</h2>";
        }

        $output .= <<<END
        <div class="mcc-map-embed map" id="mcc-map-$route->ID" data-map="" data-options="embedMapOptions">
END;
            $kml_url = get_field('kml_url', $route->ID);

            if ($kml_url !== false):
                $output .= "<div class=\"kml-layer\" data-url=\"$kml_url\"></div>";
            endif;

            while (has_sub_field('markers', $route->ID)) {
                $label = get_sub_field('label');
                $lat   = get_sub_field('lat');
                $lng   = get_sub_field('lng');

                $output .= <<<EOD
                    <div class="marker"
                         data-marker=""
                         data-lng="$lng"
                         data-lat="$lat"
                         data-title="$label">
                    </div>
EOD;
            }
            $output .= <<<EOD
        </div>
    </div>
EOD;

    return $output;
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

    $output = "";

    if ($routes):
        $output = <<<EOD
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="dropdown active">
                <a href="#" id="routeSelect" class="dropdown-toggle" data-toggle="dropdown">
                    Route Select <b class="caret"></b>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="routeSelect">
EOD;
                        $first = true;
                        foreach ($routes as $route):
                            $klass   = ($first) ? 'class="active"' : '';
                            $output .= <<<EOD
                            <li class="$klass">
                                <a href="#tab-$route->ID" data-toggle="tab">
                                   $route->post_title
                                </a>
                            </li>
EOD;
                            $first = false;
                        endforeach;
        $output .= <<<EOD
                </ul>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
EOD;
                $first = true;
                foreach ($routes as $route):
                    $klass = ($first) ? 'active in' : '';
                    $map   = do_shortcode('[mcc-map name=' . $route->post_name . ' title=0]');
                    $output .= <<<EOD
                    <div class="tab-pane fade $klass" id="tab-$route->ID">
                        $map
                    </div>
EOD;
                    $first = false;
                endforeach;
        $output .= "</div>";
    endif;

    return $output;
}
add_shortcode('mcc-map-tabs', 'mcc_map_tabs_shortcode');