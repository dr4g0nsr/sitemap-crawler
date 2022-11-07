<?php

namespace dr4g0nsr\Tests;

use PHPUnit\Framework\TestCase;
use dr4g0nsr\guzzle\GuzzleGet;

/**
 * Description of GuzzleTest
 *
 * @author drago
 */
class GuzzleTest extends TestCase {

    private $guzzleTestInstance = NULL;
    private $siteURL = 'http://candymapper.com';

    public function __construct() {
        parent::__construct();
        $this->guzzleTestInstance = new GuzzleGet();
    }

    public function testGuzzleGet() {
        $page = $this->guzzleTestInstance->guzzlePage($this->siteURL);
        $this->assertCount(4, $page);
        $this->assertEquals(200, $page[0]);
        $this->assertEquals('text/html;charset=utf-8', $page[1]);
        $this->assertGreaterThan(100, strlen($page[2]));
        $this->assertGreaterThan(100, strlen($page[3]));
    }

}
