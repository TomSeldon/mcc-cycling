<?php
/**
 * archive-location.php
 * Author: Tom Seldon
 * Created: 27/01/2014
 */

    global $wp_query;

    $big = 999999999; // need an unlikely integer

    $pagination = paginate_links(array(
        'base'          => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format'        => '?paged=%#%',
        'current'       => max( 1, get_query_var('paged') ),
        'total'         => $wp_query->max_num_pages,
        'mid_size'      => 5
    ));
?>

<div class="pagination">
    <?php echo $pagination; ?>
</div>

<?php
    while (have_posts()): the_post();
        get_template_part('templates/archive-single-location');
    endwhile;
?>

<div class="pagination">
    <?php echo $pagination; ?>
</div>