<?php
/**
 * RecordsParser.php
 * Author: Tom Seldon
 * Created: 29/01/2014
 */

class RecordsParser {

    private $documents;

    public function __construct()
    {
        $this->documents = array();

        $this->parseActivities();
        $this->parseAccommodation();

        $this->saveDocuments();
    }

    private function saveDocuments()
    {
        file_put_contents('raw/records.bin', serialize($this->documents));

        echo "Success: " . count($this->documents);
    }

    private function parseActivities()
    {
        $url_file = 'raw/activity-page-urls.bin';

        $urls = file_get_contents($url_file);
        $urls = unserialize($urls);

        foreach ($urls as $url) {
            $url = 'http://www.visitwales.com' . $url;

            $Activity = new ActivityRecordScraper($url);

            $this->documents[] = $Activity->getDocument();
        }
    }

    private function parseAccommodation()
    {
        $url_file = 'raw/accommodation-page-urls.bin';

        $urls = file_get_contents($url_file);
        $urls = unserialize($urls);

        foreach ($urls as $url) {
            $url = 'http://www.visitwales.com' . $url;

            $Accommodation = new AccommodationRecordScraper($url);

            $this->documents[] = $Accommodation->getDocument();
        }
    }
} 