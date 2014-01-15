<?php
/**
 * content-contact.php
 * Author: Tom Seldon
 * Created: 15/01/2014
 */

while (have_posts()) : the_post();
    the_content();
    wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>'));
endwhile;
