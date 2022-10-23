# ALPHA VERSION, DO NOT USE ON PRODUCTION

[![Test status](https://github.com/dr4g0nsr/sitemap-crawler/workflows/Tests/badge.svg)](https://github.com/dr4g0nsr/sitemap-crawler/actions)

## Sitemap Crawler

Crawler using sitemap to crawl site/regenerate cache.

Files are not stored, point is just to trigger url.

## Get code using composer

```
composer require dr4g0nsr\SitemapCrawler
```

## How to implement

Create config.php:

```
$settings = [
    "sleep" => 0,
    "excluded" => []
];
```

Use code like this:

```
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

use dr4g0nsr\SitemapCrawler;

$crawler = new SitemapCrawler();
$crawler->loadConfig(__DIR__ . '/config.php');
$sitemap = $crawler->getSitemap($url);
$crawler->crawlURLS($sitemap);
```

That would be simplest code, you can also find it in test subdir under vendor/dr4g0nsr/SitemapCrawler/test.
