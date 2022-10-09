<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';

use \dragutin\SitemapCrawler;

//$url = 'https://bonneli-imago-011.co.rs';
//$url = 'https://godev.link';
$url = 'https://cirko.me';
print "Crawler version: " . SitemapCrawler::version() . PHP_EOL;

$crawler = new SitemapCrawler();
$crawler->loadConfig();
$sitemap = $crawler->getSitemap($url);
$crawler->crawlURLS($sitemap);
