<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/config.php';

use dr4g0nsr\SitemapCrawler;
use \GuzzleHttp as http;

$httpClient = new \GuzzleHttp\Client();

$url = 'https://candymapper.com';
//$url = 'https://godev.link';
print "Crawler version: " . SitemapCrawler::version() . PHP_EOL;

$crawler = new SitemapCrawler(['sleep' => 5]);
$crawler->loadConfig(__DIR__ . '/config.php');
$sitemap = $crawler->getSitemap($url);
//$crawler->crawlURLS($sitemap);
var_dump($sitemap);
