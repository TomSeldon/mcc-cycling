<?php
/**
 * AccomodationUrlScraper.php
 * Author: Tom Seldon
 * Created: 29/01/2014
 */

class AccommodationUrlScraper extends PageUrlScraper {

    public function __construct()
    {
        $base_url  = 'http://www.visitwales.com/accommodation-search/accommodation-search-results?location=Monmouthshire%2c+Wales&industry=Hotels&radius=10&filterIds=&city=&price=&page=';
        $max_pages = 4;

        parent::__construct('accommodation', $base_url, $max_pages);
    }
} 