<?php

namespace dr4g0nsr\Tests;

use PHPUnit\Framework\TestCase;
use dr4g0nsr\Crawler;

final class CrawlTest extends TestCase {

    private $crawlTestInstance = NULL;

    public function __construct() {
        parent::__construct();
        $this->crawlTestInstance = new Crawler();
    }

    public function testVersionOK(): void {
        $version="1.0a";
        $this->assertEquals($version, Crawler::SC_VERSION);
        $this->assertEquals($version, Crawler::version());
    }
    
    public function testLoadConfig(): void {
        $this->assertEquals(NULL, $this->crawlTestInstance->loadConfig(__DIR__.'/config.php'));
    }
    
    public function testGetSitemap():void {
        $this->crawlTestInstance->getSitemap('https://candymapper.com');
    }

}
