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
        <div class="row">
            <div class="col-md-9 main">
                <ul class="nav nav-pills">
                    <li class="active"><a href="#event" data-toggle="tab">The Event</a></li>
                    <li><a href="#route" data-toggle="tab">The Route</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade in active" id="event">
                        <?php the_content(); ?>
                    </div>
                    <div class="tab-pane fade in" id="route">
                        route
                    </div>
                </div>
            </div>

            <div class="col-md-3 sidebar">
                <div class="summary row">
                    <h2>Summary</h2>
                    <div class="panel panel-default summary">
                        <div class="panel-body">
                            <p>Route:</p>
                            <div id="summary-map"></div>
                        </div>

                        <table class="table">
                            <tbody>
                                <tr class="date">
                                    <td>Event date:</td>
                                    <td><?php echo date('j M Y', $event_date_unix); ?></td>
                                </tr>
                                <?php if (false !== $website): ?>
                                <tr class="website">
                                    <td>Event website:</td>
                                    <td><a href="<?php echo $website; ?>"><?php echo $website; ?></a></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if(false !== $benefits && count($benefits)): ?>
                <div class="benefits row">
                    <h2>Benefits</h2>
                    <ul class="list-group">
                        <?php while (has_sub_field('benefits')): ?>
                        <li class="list-group-item benefit">
                            <?php the_sub_field('benefit'); ?>
                            <span class="glyphicon glyphicon-check pull-right"></span>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if(false !== $facilities && count($facilities)): ?>
                <div class="facilities row">
                    <h2>Facilities</h2>
                    <ul class="list-group">
                        <?php while (has_sub_field('facilities')): ?>
                        <li class="list-group-item facility">
                            <?php the_sub_field('facility'); ?>
                            <span class="glyphicon glyphicon-check pull-right"></span>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </article>

<?php endwhile; ?>