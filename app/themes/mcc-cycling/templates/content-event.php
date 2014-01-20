<?php

    $locations  = get_field('locations');
    $event_date = get_field('event_date');
    $event_date_unix = strtotime($event_date);

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

            <header class="col-xs-12">
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <div class="entry-meta">
                    <?php get_template_part('templates/entry-meta'); ?>
                </div>
            </header>

            <div class="content entry-content col-md-8 col-md-offset-2">
                <?php the_content(); ?>
            </div>

            <footer>
                <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
            </footer>
        </div>


        <?php if (false !== $locations && is_array($locations) && count($locations)): ?>
        <div class="row locations">
            <div class="col-xs-12">
                <h2><?php the_field('near_this_track_heading'); ?></h2>
            </div>
            <div class="col-xs-12">
                <?php while (has_sub_field('locations')): $image = get_sub_field('thumbnail'); ?>
                    <div class="col-sm-4 location">
                        <a href="<?php the_sub_field('url'); ?>">
                            <img alt="<?php echo $image['alt']; ?>"
                                 src="<?php echo $image['sizes']['medium']; ?>"
                                 width="285px"
                                 height="285px" />
                            <div class="label"><?php the_sub_field('name'); ?></div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>
    </article>
<?php endwhile; ?>