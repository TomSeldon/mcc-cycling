<?php
/**
 * RecordScraper.php
 * Author: Tom Seldon
 * Created: 29/01/2014
 */

abstract class RecordScraper
{
    /**
     * URL of the page to scrape.
     *
     * @var string
     */
    protected $url;

    /**
     * The HTML DOM tree of the URL.
     *
     * @var
     */
    protected $html;

    /**
     * The document to be converted to a WP Post.
     *
     * @var stdClass
     */
    protected $document;

    public function __construct($url)
    {
        $this->url      = str_replace('&amp;', '&', $url);

        $this->html     = file_get_html($this->url);

        $this->document = array(
            'post'      => $this->buildPost(),
            'post_meta' => $this->buildPostMeta()
        );
    }

    /**
     * Returns the document.
     *
     * @return stdClass
     */
    public function getDocument()
    {
        return $this->document;
    }

    abstract protected function buildPost();

    abstract protected function buildPostMeta();
}