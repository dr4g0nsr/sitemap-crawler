# ALPHA VERSION, DO NOT USE ON PRODUCTION

[![Test status](https://github.com/dr4g0nsr/sitemap-crawler/workflows/Composer/badge.svg)](https://github.com/dr4g0nsr/sitemap-crawler/actions)

## Sitemap Crawler

Crawler using sitemap to crawl site/regenerate cache.

Files are not stored, point is just to trigger url.

## Get code using composer

```
composer require dr4g0nsr/sitemap-crawler
```

## How to implement

Create config.php:

```
<?php

$settings = [
    "sleep" => 0,
    "excluded" => []
];
```

Use code like this:

```
<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

use dr4g0nsr\Crawler;

$url = 'https://candymapper.com';
print "Crawler version: " . Crawler::version() . PHP_EOL;

$crawler = new Crawler(['sleep' => 0, 'verbose' => true]);
$crawler->loadConfig(__DIR__ . '/config.php');
$sitemap = $crawler->getSitemap($url);
$crawler->crawlURLS($sitemap);
```

That would be simplest code, you can also find it in test subdir under vendor/dr4g0nsr/SitemapCrawler/test.
