<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/config.php';


use dr4g0nsr\SitemapCrawler;

//$url = 'https://bonneli-imago-011.co.rs';
$url = 'https://candymapper.com';
print "Crawler version: " . SitemapCrawler::version() . PHP_EOL;

$crawler = new SitemapCrawler(['sleep' => 5]);
$crawler->loadConfig(__DIR__ . '/config.php');
var_dump($crawler->getSettings());
die("x");

$sitemap = $crawler->getSitemap($url);
$crawler->crawlURLS($sitemap);
