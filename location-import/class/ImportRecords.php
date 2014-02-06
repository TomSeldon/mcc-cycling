<?php
/**
 * ImportRecords.php
 * Author: Tom Seldon
 * Created: 29/01/2014
 */

class ImportRecords {

    /**
     * @var string
     */
    private $record_file;

    /**
     * @var int
     */
    private $author_id;

    /**
     * @var mixed
     */
    private $records;

    public function __construct()
    {
        $this->record_file = 'raw/records.bin';

        $this->records = unserialize( file_get_contents($this->record_file) );

        $this->importRecords();
    }

    private function importRecords()
    {
        foreach ($this->records as $record) {
            $this->importRecord($record);
        }
    }

    private static function getAcfKey($fieldName)
    {
        $acfKeys = array(
            'location'          => 'field_52e588097e83b',
            'website'           => 'field_52e587fb7e83a',
            'telephone'         => 'field_52e587bf7e838',
            'email'             => 'field_52e587d87e839',
            'facilities'        => 'field_52eaa1e64b205',
            'accessibility'     => 'field_52e5875d7e835',
        );

        if (array_key_exists($fieldName, $acfKeys)) {
            return $acfKeys[$fieldName];
        } else {
            return false;
        }
    }

    /**
     * Adds a single record to the WP database.
     *
     * @param $record
     * @throws Exception
     */
    private function importRecord($record)
    {
        $post_id = $this->insertPost($record['post']);

        if (false === $post_id) {
            throw new Exception('Unable to create post.');
        }

        $this->insertPostMeta($post_id, $record['post_meta']);
        $this->insertImages($post_id, $record['post']->images);
    }

    /**
     * Attach images to post.
     *
     * @param $post_id
     * @param $images
     */
    private function insertImages($post_id, $images)
    {
        add_action('add_attachment', array($this, 'setPostThumbnail'));

        foreach ($images as $image) {
            $img = media_sideload_image($image['src'], $post_id, $image['alt']);
        }

        remove_action('add_attachment', array($this, 'setPostThumbnail'));
    }

    /**
     * Run every time an image is attached to post.
     * We check if the parent post (the location) has a featured image,
     * if it does not then we use this attachement.
     *
     * @param $attch_id
     */
    public static function setPostThumbnail($attch_id)
    {
        $attachment = get_post($attch_id);
        $location   = get_post($attachment->post_parent);

        if (!has_post_thumbnail($location->ID)) {
            update_post_meta($location->ID, '_thumbnail_id', $attch_id);
        }
    }

    /**
     * Adds the post.
     *
     * @param $post_data
     * @return int|WP_Error
     */
    private function insertPost($post_data)
    {
        $args = array(
            'post_title'    => $post_data->post_title,
            'post_content'  => $post_data->post_content,
            'post_status'   => 'publish',
            'post_type'     => $post_data->post_type,
            'post_author'   => $this->author_id,
        );

        return wp_insert_post($args);
    }

    /**
     * Add the post meta fr the newly created post.
     *
     * @param $post_id
     * @param $post_meta_data
     */
    private function insertPostMeta($post_id, $post_meta_data)
    {
        foreach ($post_meta_data as $meta_key => $meta_value) {
            $acf_key = $this->getAcfKey($meta_key);

            if (false === $acf_key) {
                update_post_meta($post_id, $meta_key, $meta_value);
            } else {
                update_field($acf_key, $meta_value, $post_id);
            }
        }
    }
} 