<?php
    // Set the background image
    $maproutes_bg_img = get_redux_field('bg_map');

    if (isset($maproutes_bg_img['url'])) {
        $maproutes_bg_img = $maproutes_bg_img['url'];
    } else {
        $maproutes_bg_img = '';
    }

    // Get vars
    $maproutes_show_cta           = get_field('maproutes_show_call_to_action');
    $maproutes_cta_button_text    = '';
    $maproutes_cta_href           = '';

    if ($maproutes_show_cta) {
        $maproutes_cta_button_text    = get_field('maproutes_cta_button_text');
        $maproutes_cta_link_type      = get_field('maproutes_cta_link_type');
        $maproutes_cta_href           = null;

        switch ($maproutes_cta_link_type) {
            case 'page':
                $maproutes_cta_href  = get_field('maproutes_cta_page');
                break;

            case 'url':
                $maproutes_cta_href  = get_field('maproutes_cta_url');
                break;
        }
    }

    // Get map type
    $map_type = get_field('map_type');
    $markers  = array();
    $routeID  = null;

    switch ($map_type) {
        case 'markers':
            $markers = mcc_get_route_markers();
            break;

        case 'strava':
            $route   = get_field('route');
            $routeID = (isset($route->ID) ? $route->ID : false);
            break;
    }
?>

<div class="container section" id="map-routes" style="background-image: url('<?php echo $maproutes_bg_img; ?>');">

    <?php if ('strava' === $map_type): ?>
        <div id="map-routes-bg" data-strava-route="" data-route-id="<?php echo $routeID; ?>"></div>
    <?php elseif ('markers' === $map_type): ?>
        <div id="map-routes-bg" class="map" data-left-margin-el="map-title" data-map="">
            <?php foreach ($markers as $marker): ?>
            <div class="marker"
                 data-marker=""
                 data-lng="<?php echo $marker['lng']; ?>"
                 data-lat="<?php echo $marker['lat']; ?>"
                 data-title="<?php echo $marker['title']; ?>">
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="hidden-xs">
        <div class="row">
            <div class="col-sm-7 col-lg-4">
                <h1 id="map-title"><?php the_field('maproutes_heading'); ?></h1>
            </div>
        </div>
        <?php if ($maproutes_show_cta): ?>
            <div class="row cta">
                <div class="col-sm-4">
            <span>
                <a href="<?php echo $maproutes_cta_href; ?>" class="redButton"><?php echo $maproutes_cta_button_text; ?></a>
            </span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
