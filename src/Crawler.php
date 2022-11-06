<?php

namespace dr4g0nsr;

use dr4g0nsr\crawler\SitemapCrawler;
use dr4g0nsr\guzzle\GuzzleGet;

/**
 * Crawl site using sitemap or list as source
 *
 * @author drago
 */
class Crawler implements ICrawler {

    const SC_VERSION = "1.0a";

    private $sitemapCrawler = NULL;
    private $guzzleGet = NULL;

    public function __construct(array $settings = []) {
        $this->guzzleGet = new GuzzleGet();
        $this->sitemapCrawler = new SitemapCrawler($this->guzzleGet, $settings);
    }

    /**
     * Static function to check version of class
     * 
     * @return string
     */
    public static function version(): string {
        return self::SC_VERSION;
    }

    /**
     * Get sitemap
     * 
     * @param string $url
     * @return array
     */
    public function getSitemap(string $url): array {
        return $this->sitemapCrawler->getSitemap($url);
    }

    /**
     * Crawl urls
     * 
     * @param array $sitemap
     * @return bool
     */
    public function crawlURLS(array $sitemap): bool {
        return $this->sitemapCrawler->crawlURLS($sitemap);
    }

    /**
     * Load custom config
     * 
     * @param type $path
     */
    public function loadConfig($path = NULL): void {
        $this->sitemapCrawler->loadConfig($path);
    }

    /**
     * Get crawling statistics
     * 
     * @return array
     */
    public function getStats(): array {
        return $this->sitemapCrawler->getStats();
    }

}
