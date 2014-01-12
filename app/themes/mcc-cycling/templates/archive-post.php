<?php
/**
 * single-news.php
 * Author: Tom Seldon
 * Created: 12/01/2014
 */

?>

<div class="row">
    <article class="news col-lg-12">
        <div class="col-md-3">
            <div class="big-date">
                <div class="date">
                    <span><?php echo get_the_time('d'); ?></span>
                </div>
                <div class="month">
                    <span><?php echo get_the_time('M'); ?></span>
                </div>
            </div>
        </div>

        <header class="col-md-8 col-md-offset-1">
            <h1><?php the_title(); ?></h1>
            <div class="entry-meta">
                <?php get_template_part('templates/entry-meta'); ?>
            </div>
        </header>

        <div class="content excerpt col-md-8 col-md-offset-1">
            <?php the_excerpt(); ?>
        </div>
    </article>
</div>