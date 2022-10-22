<?php

namespace dr4g0nsr\sitemap;

use dr4g0nsr\guzzle\GuzzleGet;

/**
 * Description of SiteMapParser
 *
 * @author drago
 */
class SiteMapParse extends GuzzleGet {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get URL list using recursive method
     * 
     * @param type $parser
     * @param type $url
     * @return boolean
     */
    protected function sitemapParserGet(&$parser, $url) {
        try {
            $parser->parseRecursive($url);
        } catch (Exception $e) {
            print $e;
            die;
        }

        $urlCounter = count($parser->getURLs());
        if ($urlCounter < 1) {
            $url = str_replace('/robots.txt', '/sitemap.xml', $url);
            try {
                $parser->parseRecursive($url);
            } catch (Exception $e) {
                print $e;
                die;
            }
        }
        $urlCounter = count($parser->getURLs());
        if ($urlCounter < 1) {
            return false;
        }
        return true;
    }

}
