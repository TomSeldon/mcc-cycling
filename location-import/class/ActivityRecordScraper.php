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

        $url_args = array();
        parse_str($this->url, $url_args);

        if (!array_key_exists('latitude', $url_args)) {
            die(var_export($url_args));
        }

        $location = array(
            'lat'           => $url_args['latitude'],
            'lng'           => $url_args['longitude'],
            'center_lat'    => $url_args['latitude'],
            'center_lng'    => $url_args['longitude'],
            'address'       => $url_args['location']
        );

        $post_meta->location         = $location;
        $post_meta->telephone        = trim($this->html->find('ul.contact-details .tel', 0)->plaintext);
        $post_meta->email            = trim($this->html->find('ul.contact-details .email', 0)->plaintext);
        $post_meta->website          = trim($this->html->find('ul.contact-details .web', 0)->href);

        return $post_meta;
    }
} 