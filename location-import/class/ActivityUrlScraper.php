<?php
/**
 * ActivityUrlScraper.php
 * Author: Tom Seldon
 * Created: 29/01/2014
 */

class ActivityUrlScraper extends PageUrlScraper {

    public function __construct()
    {
        $base_url  = 'http://www.visitwales.com/attraction-search/attraction-search-results?location=Monmouthshire%2c+Wales&industry=TEA&radius=10&filterIds=&city=&price=&page=';
        $max_pages = 2;

        parent::__construct('activity', $base_url, $max_pages);
    }
} 