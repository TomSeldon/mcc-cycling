<?php
/**
 * archive-single-location.php
 * Author: Tom Seldon
 * Created: 27/01/2014
 */

    $location = get_field('location');
?>

<article class="location row">
    <div class="image col-sm-3">
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('thumbnail', array('class' => 'thumbnail')); ?>
        </a>
    </div>
    <div class="main col-sm-9">
        <header><h2><?php the_title(); ?></h2></header>
        <div class="description">
            <?php the_field('description'); ?>
        </div>
        <div class="location">
            <?php echo $location['address']; ?>
        </div>
        <div class="read-more">
            <a href="<?php the_permalink(); ?>">Read more</a>
        </div>
    </div>
</article>