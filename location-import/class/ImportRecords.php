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
            break;
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
            update_post_meta($post_id, $meta_key, $meta_value);
        }
    }
} 