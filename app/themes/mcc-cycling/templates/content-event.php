<?php

    $show_route = get_field('show_route');
    $route      = get_Field('route');
    $routeID    = (isset($route->ID) ? $route->ID : false);
    $locations  = get_field('locations');
    $event_date = get_field('event_date');
    $event_date_unix = strtotime($event_date);

    while (have_posts()) : the_post(); ?>
    <article <?php post_class(); ?>>
        <div class="row">
            <div class="col-xs-12">
                <div class="big-date">
                    <div class="date">
                        <span><?php echo date('d', $event_date_unix); ?></span>
                    </div>
                    <div class="month">
                        <span><?php echo date('M', $event_date_unix); ?></span>
                    </div>
                </div>
            </div>

            <header class="col-xs-12">
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <div class="entry-meta">
                    <?php get_template_part('templates/entry-meta'); ?>
                </div>
            </header>

            <?php if (false !== $show_route): ?>
            <div class="route col-md-8 col-md-offset-2">
                <div id="event-route" data-strava-route="" data-route-id="<?php echo $routeID; ?>"></div>
            </div>
            <?php endif; ?>

            <div class="content entry-content col-md-8 col-md-offset-2">
                <?php the_content(); ?>
            </div>

            <footer>
                <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
            </footer>
        </div>


        <?php if (false !== $locations && is_array($locations) && count($locations)): ?>
        <div class="row locations">
            <div class="col-xs-12">
                <h2><?php the_field('near_this_track_heading'); ?></h2>
            </div>
            <div class="col-xs-12">
                <?php foreach($locations as $location): ?>
                    <div class="col-sm-4 location">
                        <a href="<?php echo get_permalink($location); ?>">
                            <?php
                            if (has_post_thumbnail($location->ID)) {
                                echo get_the_post_thumbnail($location->ID);
                            }
                            ?>
                            <div class="label"><?php echo $location->post_title; ?></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </article>
<?php endwhile; ?>
