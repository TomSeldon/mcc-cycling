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

    $locations = mcc_get_locations(get_post_type());
?>

<?php if (count($locations)): ?>
<div class="view-on-map">
    <a href="#map-container" class="redButton">View all on map</a>
</div>
<?php endif; ?>

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

<?php if (count($locations)): ?>
    <div id="map-container">
        <h2>Map of locations</h2>
        <div id="map-locations" class="map" data-map="" data-options="locationsMapSettings">
            <?php foreach ($locations as $location): ?>
                <div class="marker sr-only"
                     data-marker=""
                     data-lng="<?php echo $location['lng']; ?>"
                     data-lat="<?php echo $location['lat']; ?>"
                     data-address="<?php echo $location['address']; ?>"
                     data-title="<?php echo $location['post']->post_title; ?>">

                    <div class="entry">
                        <?php if (has_post_thumbnail($location['post']->ID)): ?>
                            <div class="location-thumb pull-left">
                                <a href="<?php echo get_permalink($location['post']->ID); ?>">
                                    <?php echo get_the_post_thumbnail($location['post']->ID, 'thumbnail', array('class' => 'thumbnail')); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="title pull-left">
                            <h4><?php echo $location['post']->post_title; ?></h4>
                        </div>
                    </div>

                    <div class="info pull-left">
                        <p><a href="<?php echo get_permalink($location['post']->ID); ?>">Click here</a> for more information.</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>