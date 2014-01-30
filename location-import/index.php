<?php
/**
 * index.php
 * Author: Tom Seldon
 * Created: 29/01/2014
 */

require_once "../vendor/autoload.php";
require_once "class/PageUrlScraper.php";
require_once "class/AccommodationUrlScraper.php";
require_once "class/ActivityUrlScraper.php";
require_once "class/RecordScraper.php";
require_once "class/AccommodationRecordScraper.php";
require_once "class/ActivityRecordScraper.php";
require_once "class/RecordsParser.php";
require_once "class/ImportRecords.php";
require_once "../wp/wp-load.php";
require_once "../wp/wp-admin/includes/file.php";
require_once "../wp/wp-admin/includes/image.php";
require_once "../wp/wp-admin/includes/media.php";

// Get URLs
//new AccommodationUrlScraper();
//new ActivityUrlScraper();

// Scrape and parse individual records
//new RecordsParser();

// Import posts
new ImportRecords();