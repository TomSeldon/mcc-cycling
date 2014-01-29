<?php
/**
 * ActivityRecordScraper.php
 * Author: Tom Seldon
 * Created: 29/01/2014
 */

class ActivityRecordScraper extends RecordScraper
{
    protected function buildPost()
    {
        $post = new stdClass();

        $post->post_title       = trim($this->html->find('section.longform h1', 0)->plaintext);
        $post->post_content     = trim($this->html->find('.product-description', 0)->innertext);
        $post->post_type        = 'mcc_location_activity';
        $post->images           = array();

        foreach ($this->html->find('.carousel-products ul.slides li') as $image) {
            $post->images[] = array(
                'alt'   => $image->find('img', 0)->alt,
                'src'   => $image->find('img', 0)->src
            );
        }

        return $post;
    }

    protected function buildPostMeta()
    {
        $post_meta = new stdClass();

        $post_meta->address          = trim($this->html->find('section.longform h3', 0)->plaintext);
        $post_meta->telephone        = trim($this->html->find('ul.contact-details .tel', 0)->plaintext);
        $post_meta->email            = trim($this->html->find('ul.contact-details .email', 0)->plaintext);
        $post_meta->website          = trim($this->html->find('ul.contact-details .web', 0)->href);

        return $post_meta;
    }
} 