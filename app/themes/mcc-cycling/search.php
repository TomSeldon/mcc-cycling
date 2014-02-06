<?php
/**
 * search.php
 * Author: Tom Seldon
 * Created: 06/02/2014
 */

global $wp_query;

if (!have_posts()) : ?>
    <div class="alert alert-warning">
        <?php _e('Sorry, no results were found.', 'roots'); ?>
    </div>
    <?php get_search_form(); ?>
<?php endif; ?>

<div class="search-form row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3">
        <h3><?php _e('Search again', 'mcc'); ?></h3>
        <?php get_search_form(); ?>
    </div>
</div>

<?php
    while (have_posts()) : the_post();
        get_template_part('templates/content', 'search');
    endwhile;
?>

<?php if ($wp_query->max_num_pages > 1) : ?>
    <nav class="post-nav">
        <ul class="pager col-md-9 col-md-offset-3">
            <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></li>
            <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></li>
        </ul>
    </nav>
<?php endif; ?>
