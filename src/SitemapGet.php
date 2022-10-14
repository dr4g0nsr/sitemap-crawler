<?php

namespace dr4g0nsr;

use vipnytt\SitemapParser;

/**
 * Description of SitemapParser
 *
 * @author drago
 */
class SitemapGet {

    const SC_VERSION = "1.0a";

    private $agentID = 'Sitemap Crawler ' . self::SC_VERSION;

    /**
     * Get URL list using recursive method
     * 
     * @param type $parser
     * @param type $url
     * @return boolean
     */
    private function sitemapParserGet(&$parser, $url) {
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

    /**
     * Get sitemap and URL list using SitemapParser class
     * 
     * @param string $url Base URL
     * @return array Array of sources and urls
     */
    public function sitemapParser(string $url): array {
        $parser = new SitemapParser($this->agentID, ['guzzle' => ['defaults' => [
                    'verify' => false,
                    'connect_timeout' => 5,
                    'timeout' => 10,
                ], 'headers' => ['Accept-Encoding' => 'gzip, deflate']]]);
        $this->sitemapParserGet($parser, $url);
        $sitemaps = [];
        foreach ($parser->getSitemaps() as $url => $tags) {
            $sitemaps[$url] = $tags;
        }
        $urls = [];
        foreach ($parser->getURLs() as $url => $tags) {
            $urls[$url] = $tags;
        }
        return ['sources' => $sitemaps, "urls" => $urls];
    }

}
