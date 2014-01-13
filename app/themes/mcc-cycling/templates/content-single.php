<?php while (have_posts()) : the_post(); ?>
    <article <?php post_class(); ?>>
        <div class="row">
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

            <header class="col-md-9">
                <h1><?php the_title(); ?></h1>
                <div class="entry-meta">
                    <?php get_template_part('templates/entry-meta'); ?>
                </div>
            </header>

            <div class="content entry-content col-md-9">
                <?php the_content(); ?>
            </div>

            <footer>
                <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
            </footer>
            <?php comments_template('/templates/comments.php'); ?>
        </div>
    </article>
<?php endwhile; ?>
