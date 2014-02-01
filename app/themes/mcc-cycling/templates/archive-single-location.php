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
        <header>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        </header>
        <div class="description">
            <?php the_excerpt(); ?>
        </div>
    </div>
</article>