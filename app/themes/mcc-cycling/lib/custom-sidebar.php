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
            }
        }
    }
}

function mcc_sidebar_show_locations_layout($locations)
{
    echo '<div class="locations">';

    if (true === get_sub_field('show_heading'));
        echo "<h2>" . get_sub_field('heading') . "</h2>";

    foreach ($locations as $location) {
        echo do_shortcode("[mcc-location id=$location->ID]");
    }

    echo '</div>';
}