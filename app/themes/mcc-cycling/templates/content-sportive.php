<?php
/**
 * content-sportive.php
 * Author: Tom Seldon
 * Created: 28/01/2014
 */

    const USE_STRAVA_MAP = false;

    $event_date         = get_field('event_date');
    $event_date_unix    = strtotime($event_date);
    $datediff           = $event_date_unix - time();
    $days_until_event   = ceil($datediff/(60*60*24));

    $benefits           = get_field('benefits');
    $facilities         = get_field('facilities');
    $location           = get_field('event_location');
    $website            = get_field('event_website');
    $route              = get_field('route');
    $routes             = get_field('routes');

    if ($days_until_event > 0) {
        $ended = false;
        $days_until_event_str = sprintf(__('%d %s until the event', 'mcc'), $days_until_event, _n('day', 'days', $days_until_event, 'mcc'));
    } else {
        $ended = true;
        $days_until_event_str = __('This event has ended', 'mcc');
    }

while (have_posts()) : the_post(); ?>

    <article <?php post_class(); ?>>
        <div class="row">
            <div class="col-xs-12">
                <div class="date row">
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
                    <div class="col-xs-12 time-until">
                        <h2><?php echo $days_until_event_str; ?></h2>
                    </div>
                    <?php if (!$ended): ?>
                        <div class="add-to-cal col-xs-12">
                            <a href="http://example.com/link-to-your-event" title="Add to Calendar" class="addthisevent">
                                Add to Calendar
                                <span class="_start"><?php echo date('d-m-Y', $event_date_unix); ?></span>
                                <span class="_end"><?php echo date('d-m-Y', $event_date_unix); ?></span>
                                <span class="_zonecode">36</span>
                                <span class="_summary"><?php the_title(); ?></span>
                                <span class="_description"><?php echo strip_tags(get_the_excerpt()); ?></span>

                                <?php if (false !== $location): ?>
                                    <span class="_location"><?php echo $location['address']; ?></span>
                                <?php endif; ?>

                                <span class="_all_day_event">true</span>
                                <span class="_date_format">DD/MM/YYYY</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-9 main">
                <div id="event">
                    <div class="page-header">
                        <h2>The Event </h2>
                    </div>

                    <?php the_content(); ?>
                </div>

                <div id="route">
                    <div class="page-header">
                        <h2>The <?php echo _n('Route', 'Routes', count($routes)); ?></h2>
                    </div>

                    <?php if ($route !== false && USE_STRAVA_MAP === true): ?>
                    <h4 class="route-name">
                        <?php echo $route->post_title; ?>&nbsp;
                    </h4>
                    <small class="view-on-strava">
                        <a target="_blank" href="<?php the_field('url', $route->ID); ?>">
                            View route on Strava.com
                        </a>
                    </small>

                    <div class="sportive-route-lg" data-strava-route="" data-route-id="<?php echo $route->ID; ?>"></div>

                    <?php elseif ($routes !== false):
                        $routeNames = array();

                        while (has_sub_field('routes')) {
                            $route = get_sub_field('route');
                            $routeNames[] = $route->post_name;
                        }

                        if (count($routeNames)) {
                            $routeNames = implode(',', $routeNames);
                            echo do_shortcode("[mcc-map-tabs names='$routeNames']");
                        }
                    endif; ?>
                </div>
            </div>

            <div class="col-md-3 sidebar sign-up">
                <a class="redButton" href="<?php the_redux_field('login_url'); ?>">Sign up now</a>
            </div>

            <div class="col-md-3 sidebar">
                <?php if(false !== $facilities && count($facilities)): ?>
                    <div class="facilities row">
                        <h2>Included</h2>
                        <ul class="list-group">
                            <?php while (has_sub_field('facilities')): ?>
                                <li class="facility clearfix">
                                    <span class="item"><?php the_sub_field('facility'); ?></span>
                                    <span class="tick glyphicon glyphicon-check"></span>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php get_template_part('templates/addthis', 'share'); ?>
            </div>
        </div>
    </article>

<?php endwhile; ?>