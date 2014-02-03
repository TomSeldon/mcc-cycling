<?php
/**
 * custom-sidebar.php
 * Author: Tom Seldon
 * Created: 03/02/2014
 */

function mcc_show_custom_sidebar()
{
    if ( have_rows('sidebar_content') ) {
        while ( have_rows('sidebar_content') ) {
            the_row();

            $row_type = get_row_layout();

            switch ($row_type) {
                case 'locations_cta':
                    mcc_sidebar_show_locations_layout( get_sub_field('locations') );
                    break;

                case 'thumbnail_and_links':
                    mcc_sidebar_show_thumb_and_link_layout();
                    break;
            }
        }
    }
}

function mcc_sidebar_show_thumb_and_link_layout()
{
    echo '<div class="thumb-links cta">';
    echo '  <h2>' . get_sub_field('heading') . '</h2>';


    $thumbnail      = get_sub_field('thumbnail');
    $thumb_alt      = $thumbnail['title'];
    $thumb_src      = $thumbnail['sizes']['medium'];
    $thumb_width    = $thumbnail['sizes']['medium-width'];
    $thumb_height   = $thumbnail['sizes']['medium-height'];

    echo "<img class='thumbnail' src='$thumb_src' alt='$thumb_alt' width='$thumb_width' height='$thumb_height' />'";

    echo '<div class="intro-text">';
        the_sub_field('intro_text');
    echo '</div>';

    echo '<ul class="links">';

    while (has_sub_field('links')) {
        $link_type  = get_sub_field('link_type');
        $link_href  = false;
        $link_label = get_sub_field('label');

        switch ($link_type) {
            case 'internal':
                $linked_posts = get_sub_field('internal_link');

                foreach ($linked_posts as $linked_post) {
                    $link_href   = get_permalink($linked_post->ID);
                    break;
                }

                break;

            case 'external';
                $link_href = get_sub_field('external_url');
                break;
        }

        if (false !== $link_href && false !== $link_label)
            echo "<li><a href='$link_href'>$link_label<i class='read-more fa fa-chevron-right'></i></a></li>";
    }

    echo '</ul>';

    echo '</div>';
}

function mcc_sidebar_show_locations_layout($locations)
{
    echo '<div class="locations row"><div class="col-xs-12">';

    if (true === get_sub_field('show_heading'));
        echo "<h2>" . get_sub_field('heading') . "</h2>";

    foreach ($locations as $location) {
        echo do_shortcode("[mcc-location id=$location->ID show_container=0]");
    }

    echo '</div></div>';
}