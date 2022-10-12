# ALPHA VERSION, DO NOT USE ON PRODUCTION

# Sitemap Crawler

Crawler using sitemap to crawl site/regenerate cache.

Files are not stored, point is just to trigger url.

## How to implement

Use code like this:

```
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

use dr4g0nsr\SitemapCrawler;

$url = 'https://candymapper.com';
print "Crawler version: " . SitemapCrawler::version() . PHP_EOL;

$crawler = new SitemapCrawler(['sleep' => 5]);
$crawler->loadConfig(__DIR__ . '/config.php');
$sitemap = $crawler->getSitemap($url);
$crawler->crawlURLS($sitemap);
```

That would be simplest code, you can also find it in test subdir under vendor/dr4g0nsr/SitemapCrawler.
