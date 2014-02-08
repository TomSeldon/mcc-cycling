<?php
/**
 * archive-single-location.php
 * Author: Tom Seldon
 * Created: 27/01/2014
 */

    $location = get_field('location');
    $mapElID  = "map-popup-" . $post->ID;
?>

<article class="location row">
    <div class="image col-sm-3">
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('thumbnail', array('class' => 'thumbnail')); ?>
        </a>
    </div>

    <div class="main col-sm-9">
        <header>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        </header>
        <div class="description">
            <?php the_excerpt(); ?>

            <a
                disabled="disabled"
                class="viewOnMap"
                href="#<?php echo $mapElID; ?>">
                View on map <i class="fa fa-map-marker"></i>
            </a>
        </div>
    </div>

    <div class="popup-element mfp-hide white-popup" id="<?php echo $mapElID; ?>">
        <h1>test</h1>
        <div class="map">test</div>
    </div>
</article>