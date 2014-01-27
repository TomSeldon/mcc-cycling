<?php
/**
 * archive-location.php
 * Author: Tom Seldon
 * Created: 27/01/2014
 */

    while (have_posts()): the_post();
        get_template_part('templates/archive-single-location');
    endwhile;
