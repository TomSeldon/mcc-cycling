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

    if ($days_until_event > 0) {
        $days_until_event_str = sprintf(__('%d %s until the event', 'mcc'), $days_until_event, _n('day', 'days', $days_until_event, 'mcc'));
    } else {
        $days_until_event_str = __('This event has ended', 'mcc');
    }

while (have_posts()) : the_post(); ?>

    <article <?php post_class(); ?>>
        <div class="row">
            <div class="col-xs-12 col-md-3 sidebar pull-right">
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
                    <h4>
                        <?php echo $route->post_title; ?>&nbsp;
                        <small>(<a target="_blank" href="<?php the_field('url', $route->ID); ?>">View route on Strava.com</a>)</small>
                    </h4>
                    <div id="sportive-route-lg" data-strava-route="" data-route-id="<?php echo $route->ID; ?>"></div>
                </div>
            </div>

            <div class="col-md-3 sidebar">
                <div class="share row">
                    <h2>Share</h2>

                    <!-- AddThis Button BEGIN -->
                    <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                        <a class="addthis_button_preferred_1"></a>
                        <a class="addthis_button_preferred_2"></a>
                        <a class="addthis_button_preferred_3"></a>
                        <a class="addthis_button_preferred_4"></a>
                        <a class="addthis_button_compact"></a>
                        <a class="addthis_counter addthis_bubble_style"></a>
                    </div>
                    <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52e94e7e419949f1"></script>
                    <!-- AddThis Button END -->
                </div>

                <div class="summary row">
                    <h2>Summary</h2>
                    <div class="panel panel-default summary">
                        <table class="table">
                            <tbody>
                                <tr class="date">
                                    <td>Date:</td>
                                    <td><?php echo date('j M Y', $event_date_unix); ?></td>
                                </tr>

                                <?php if (false !== $location && !empty($location['address'])): ?>
                                    <tr class="address">
                                        <td>Location:</td>
                                        <td><?php echo $location['address']; ?></td>
                                    </tr>
                                <?php endif; ?>

                                <?php if (false !== $website): ?>
                                <tr class="website">
                                    <td>Website:</td>
                                    <td><a href="<?php echo $website; ?>"><?php echo $website; ?></a></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if(false !== $facilities && count($facilities)): ?>
                <div class="facilities row">
                    <h2>Facilities</h2>
                    <ul class="list-group">
                        <?php while (has_sub_field('facilities')): ?>
                        <li class="list-group-item facility clearfix">
                            <span class="item"><?php the_sub_field('facility'); ?></span>
                            <span class="tick glyphicon glyphicon-check"></span>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </article>

<?php endwhile; ?>