<?php
/**
 * content-search.php
 * Author: Tom Seldon
 * Created: 27/01/2014
 */

?>

<article class="search-result row">
    <div class="image col-xs-12 col-sm-3 col-lg-2">
        <?php if (has_post_thumbnail()): ?>
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('thumbnail', array('class' => 'thumbnail')); ?>
        </a>
        <?php endif; ?>
    </div>
    <div class="main col-xs-12 col-sm-9 col-lg-10">
        <header>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        </header>
        <div class="description">
            <?php the_excerpt(); ?>
        </div>
    </div>
</article>