<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';

use \dr4g0nsr\SitemapCrawler;

//$url = 'https://bonneli-imago-011.co.rs';
//$url = 'https://godev.link';
$url = 'https://cirko.me';
print "Crawler version: " . SitemapCrawler::version() . PHP_EOL;

$crawler = new SitemapCrawler();
$crawler->loadConfig(__DIR__ . '/config.php');
$sitemap = $crawler->getSitemap($url);
$crawler->crawlURLS($sitemap);
