<?php
/**
 * PageUrlScraper.php
 * Author: Tom Seldon
 * Created: 29/01/2014
 */

abstract class PageUrlScraper
{
    /**
     * @var string
     */
    private $type;

    /**
     * The base URL to index.
     *
     * @var string
     */
    protected $base_url;

    /**
     * The current page being indexer
     *
     * @var int
     */
    protected $page;

    /**
     * The number of pages to loop through.
     *
     * @var int
     */
    private $max_pages;

    /**
     * An array of URLs that have been scraped from each page.
     *
     * @var array
     */
    protected $page_urls;

    public function __construct($type, $base_url, $max_pages)
    {
        $this->type      = $type;
        $this->page      = 1;
        $this->base_url  = $base_url;
        $this->max_pages = $max_pages;
        $this->page_urls = array();

        $this->parsePages();
        $this->saveUrls();
    }

    /**
     * Loop through the pages and parse each one.
     */
    protected function parsePages()
    {
        while ($this->page <= $this->max_pages) {
            $this->parsePage($this->page);
            $this->page++;
        }
    }

    /**
     * Save the parsed URLs.
     */
    protected function saveUrls()
    {
        file_put_contents('raw/' . $this->type . '-page-urls.bin', serialize($this->page_urls));
    }

    /**
     * Parse an individual page.
     *
     * @param $page_number
     * @return mixed
     */
    protected function parsePage($page_number)
    {
        $url    = $this->base_url . $page_number;
        $html   = file_get_html($url);

        foreach ($html->find('ul.results-list', 0)->children() as $result) {
            $page_url = $result->find('h4 a', 0)->href;

            if (!$page_url) {
                die($result);
            }

            if ($page_url && !in_array($page_url, $this->page_urls)) {
                $this->page_urls[] = $page_url;
            }
        }
    }
} 