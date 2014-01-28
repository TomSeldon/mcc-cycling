<?php
/**
 * single-location.php
 * Author: Tom Seldon
 * Created: 26/01/2014
 */

    $telephone  = get_field('telephone');
    $website    = get_field('website');
    $email      = get_field('email');
    $location   = get_field('location');
    $facilities = get_the_terms($post->ID, 'facility');

?>

<div class="col-sm-8">
    <h2>Contact information</h2>
    <div class="panel panel-default">
        <table class="table">
            <?php if (false !== $telephone): ?>
                <tr>
                    <td><?php _e('Telephone number:'); ?></td>
                    <td><?php echo $telephone ?></td>
                </tr>
            <?php endif; ?>
            <?php if (false !== $website): ?>
                <tr>
                    <td><?php _e('Website:'); ?></td>
                    <td><a href="<?php echo $website; ?>" target="_blank"><?php echo $website ?></a></td>
                </tr>
            <?php endif; ?>
            <?php if (false !== $email): ?>
                <tr>
                    <td><?php _e('E-mail address:'); ?></td>
                    <td><a href="mailto:<?php echo $email; ?>"><?php echo $email ?></a></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <div class="content">
        <?php echo $post->post_content; ?>
    </div>

    <?php the_content(); ?>
</div>

<aside class="col-sm-4">
    <?php if (!empty($location)): ?>
    <div class="location">
        <h2>Location</h2>
        <div class="map" data-map="">
             <div class="marker"
                  data-marker=""
                  data-lng="<?php echo $location['lng']; ?>"
                  data-lat="<?php echo $location['lat']; ?>"
                  data-address="<?php echo $location['address']; ?>">
             </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (count($facilities)): ?>
    <h2>Facilities</h2>
    <ul class="list-group">
        <?php foreach ($facilities as $facility): ?>
            <li class="list-group-item"><?php echo $facility->name; ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <?php echo get_the_post_thumbnail($post->ID, 'medium', array('class' => 'thumbnail pull-left')); ?>
</aside>