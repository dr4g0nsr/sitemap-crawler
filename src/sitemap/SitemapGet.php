<?php

namespace dr4g0nsr\sitemap;

use vipnytt\SitemapParser;

/**
 * Description of SitemapParser
 *
 * @author drago
 */
class SitemapGet extends SiteMapParse {

    const SC_VERSION = "1.0a";

    private $agentID = 'Sitemap Crawler ' . self::SC_VERSION;
    protected $parser;
    
    public function __construct() {
        parent::__construct();
        $this->initParser();
    }
    
    /**
     * Initialize parser with basic stuff
     * 
     * @return void Nothing is returned
     */
    protected function initParser():void {
        $this->parser = new SitemapParser($this->agentID, ['guzzle' => ['defaults' => [
                    'verify' => false,
                    'connect_timeout' => 5,
                    'timeout' => 10,
                ], 'headers' => ['Accept-Encoding' => 'gzip, deflate']]]);
    }

    /**
     * Get sitemap and URL list using SitemapParser class
     * 
     * @param string $url Base URL
     * @return array Array of sources and urls
     */
    public function sitemapParser(string $url): array {
        $this->sitemapParserGet($this->parser, $url);
        $sitemaps = [];
        foreach ($this->parser->getSitemaps() as $url => $tags) {
            $sitemaps[$url] = $tags;
        }
        $urls = [];
        foreach ($this->parser->getURLs() as $url => $tags) {
            $urls[$url] = $tags;
        }
        return ['sources' => $sitemaps, "urls" => $urls];
    }

}
