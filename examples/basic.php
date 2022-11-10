<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/config.php';

use dr4g0nsr\Crawler;

$url = 'https://candymapper.com';
print "Crawler version: " . Crawler::version() . PHP_EOL;

$crawler = new Crawler();
$crawler->loadConfig(__DIR__ . '/config.php');
$sitemap = $crawler->getSitemap($url);
$crawler->crawlURLS($sitemap);
