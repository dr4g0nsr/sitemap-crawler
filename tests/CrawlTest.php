<?php

namespace dr4g0nsr\Tests;

use PHPUnit\Framework\TestCase;
use dr4g0nsr\Crawler;

final class CrawlTest extends TestCase {

    private $crawlTestInstance = NULL;
    private $siteURL = 'http://candymapper.com';

    public function __construct() {
        parent::__construct();
        $this->crawlTestInstance = new Crawler();
    }

    public function testVersionOK(): void {
        $version = "1.0";
        $this->assertEquals($version, Crawler::SC_VERSION);
        $this->assertEquals($version, Crawler::version());
    }

    public function testLoadConfig(): void {
        $this->assertEquals(NULL, $this->crawlTestInstance->loadConfig(__DIR__ . '/config.php'));
    }

    public function testGetSitemap(): void {
        $sitemap = $this->crawlTestInstance->getSitemap($this->siteURL);
        $this->assertNotFalse($sitemap);
        $this->assertNotEmpty($sitemap);
        $this->assertNotEmpty($sitemap['sources']);
        $this->assertNotEmpty($sitemap['sources']['https://candymapper.com/sitemap.website.xml']);
        $this->assertNotEmpty($sitemap['sources']['https://candymapper.com/sitemap.website.xml']['loc']);
        //var_dump($sitemap);
    }

    public function testCrawlUrl(): void {
        $sitemap = $this->crawlTestInstance->getSitemap($this->siteURL);
        $this->assertNotEmpty($sitemap['sources']['https://candymapper.com/sitemap.website.xml']['loc']);
        $loc = $sitemap['sources']['https://candymapper.com/sitemap.website.xml']['loc'];
        $sitemap1 = $this->crawlTestInstance->getSitemap($loc);
        $this->crawlTestInstance->crawlURLS($sitemap1);
    }

    public function testStats(): void {
        $sitemap = $this->crawlTestInstance->getSitemap($this->siteURL);
        $this->assertNotEmpty($sitemap['sources']['https://candymapper.com/sitemap.website.xml']['loc']);
        $loc = $sitemap['sources']['https://candymapper.com/sitemap.website.xml']['loc'];
        $sitemap1 = $this->crawlTestInstance->getSitemap($loc);
        $this->crawlTestInstance->crawlURLS($sitemap1);
        $stats = $this->crawlTestInstance->getStats();
        $this->assertGreaterThan(1, $stats['ok']);
        $this->assertEquals(0, $stats['bad']);
    }

}
