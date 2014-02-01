<?php
/**
 * single-location.php
 * Author: Tom Seldon
 * Created: 26/01/2014
 */

    $telephone      = get_field('telephone');
    $website        = get_field('website');
    $email          = get_field('email');
    $location       = get_field('location');

    $facilities     = get_field('facilities');
    $accessibility  = get_field('accessibility');

    while (have_posts()): the_post();
?>

<div class="col-sm-8">
    <h2>Contact information</h2>
    <div class="panel panel-default">
        <table class="table">
            <?php if (false !== $telephone && strlen($telephone)): ?>
                <tr>
                    <td><?php _e('Telephone number:'); ?></td>
                    <td><?php echo $telephone ?></td>
                </tr>
            <?php endif; ?>
            <?php if (false !== $website && strlen($website)): ?>
                <tr>
                    <td><?php _e('Website:'); ?></td>
                    <td><a href="<?php echo $website; ?>" target="_blank"><?php echo $website ?></a></td>
                </tr>
            <?php endif; ?>
            <?php if (false !== $email && strlen($email)): ?>
                <tr>
                    <td><?php _e('E-mail address:'); ?></td>
                    <td><a href="mailto:<?php echo $email; ?>"><?php echo $email ?></a></td>
                </tr>
            <?php endif; ?>
            <?php if (false !== $location && !empty($location['address'])): ?>
                <tr>
                    <td><?php _e('Location:'); ?></td>
                    <td><?php echo $location['address']; ?></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <div class="content">
        <?php the_content(); ?>
    </div>

    <?php if (false !== $accessibility && strlen(trim(strip_tags($accessibility)))): ?>
        <div class="facilities">
            <h2>Accessibility</h2>
            <?php echo $accessibility; ?>
        </div>
    <?php endif; ?>
</div>

<aside class="col-sm-4">
    <?php if (!empty($location) && !empty($location['address'])): ?>
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

    <?php if (has_post_thumbnail()): ?>
    <div class="featured-image clearfix">
        <h2>Featured image</h2>
        <?php echo get_the_post_thumbnail($post->ID, 'medium', array('class' => 'thumbnail pull-left')); ?>
    </div>
    <?php endif; ?>

    <?php if (false !== $facilities && strlen(trim(strip_tags($facilities)))): ?>
        <div class="facilities">
            <h2>Facilities</h2>
            <?php echo $facilities; ?>
        </div>
    <?php endif; ?>
</aside>

<?php endwhile; ?>