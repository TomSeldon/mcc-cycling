<?php
/**
 * content-sportive.php
 * Author: Tom Seldon
 * Created: 28/01/2014
 */

    $event_date         = get_field('event_date');
    $event_date_unix    = strtotime($event_date);
    $datediff           = $event_date_unix - time();
    $days_until_event   = ceil($datediff/(60*60*24));

    $benefits           = get_field('benefits');
    $facilities         = get_field('facilities');
    $location           = get_field('event_location');
    $website            = get_field('event_website');
    $route              = get_field('route');

    $markers            = mcc_get_route_markers();

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
                    <?php if ($ended): ?>
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
                        <h2>The Route</h2>
                    </div>

                    <!--
                    <h4 class="route-name">
                        <?php echo $route->post_title; ?>&nbsp;
                    </h4>
                    <small class="view-on-strava">
                        <a target="_blank" href="<?php the_field('url', $route->ID); ?>">
                            View route on Strava.com
                        </a>
                    </small>
                    -->

                    <div id="sportive-route-lg" class="map" data-map="">
                        <?php foreach ($markers as $marker): ?>
                            <div class="marker"
                                 data-marker=""
                                 data-lng="<?php echo $marker['lng']; ?>"
                                 data-lat="<?php echo $marker['lat']; ?>"
                                 data-title="<?php echo $marker['title']; ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!--
                    <div id="sportive-route-lg" data-strava-route="" data-route-id="<?php echo $route->ID; ?>"></div>
                    -->
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

                <div class="share row">
                    <h2>Share with a friend</h2>

                    <!-- AddThis Button BEGIN -->
                    <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                        <a class="addthis_button_facebook">
                            <i class="fa fa-facebook"></i>
                            <span class="sr-only">Facebook</span>
                        </a>
                        <a class="addthis_button_twitter">
                            <i class="fa fa-twitter"></i>
                            <span class="sr-only">Twitter</span>
                        </a>
                        <a class="addthis_button_email">
                            <i class="fa fa-envelope"></i>
                            <span class="sr-only">E-mail</span>
                        </a>
                        <a class="addthis_button_print">
                            <i class="fa fa-print"></i>
                            <span class="sr-only">Print</span>
                        </a>
                        <a class="addthis_button_compact">
                            <i class="fa fa-plus"></i>
                            <span class="sr-only">More services</span>
                        </a>
                    </div>
                    <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52e94e7e419949f1"></script>
                    <!-- AddThis Button END -->
                </div>
            </div>
        </div>
    </article>

<?php endwhile; ?>