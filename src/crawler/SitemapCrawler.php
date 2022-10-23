<?php

declare(strict_types=1);

namespace dr4g0nsr\crawler;

use dr4g0nsr\sitemap\SitemapGet;
use dr4g0nsr\guzzle\GuzzleGet;

/**
 * Main class for sitemap crawl
 *
 * @author dragonmen@gmail.com
 */
class SitemapCrawler extends SiteMapGet implements ISitemapCrawler {

    private $sleep = 0;
    private $excluded = [];
    private $okURLs;
    private $badURLs;
    private $cnt;
    private $total;
    private $lowestTime = 999;
    private $lowestURL = '';
    private $highestTime = 0;
    private $highestURL = '';
    private $elapsed = 0;
    private $rate;
    private $guzzleGet = NULL;

    use TCrawlerBasic;

    /**
     * Inject settings here using array, preferably loaded from config or automated
     * 
     * @param array $settings Basic settings, can be parsed from config
     */
    public function __construct(GuzzleGet $guzzleGet, array $settings = []) {
        $this->guzzleGet = $guzzleGet;
        $this->checkPrerequisites();
        $this->makeSettings($settings);
        parent::__construct();
    }

    private function makeSettings(array $settings): void {
        if (empty($settings)) {
            return;
        }
        foreach ($settings as $setting => $value) {
            $this->$setting = $value;
        }
    }

    /**
     * Get list of URLs using robots.txt or sitemap.xml
     * 
     * First try to use robots.txt, if it doesn't have sitemap.xml
     * path defined (it should be) then use /sitemap.xml directly
     * 
     * @param string $url Base url, without suffix
     * @return array Array of URLs to crawl
     */
    public function getSitemap(string $url): array {
        $parse = parse_url($url);
        if (!isset($parse['path']) || $parse['path'] == '') {
            $robotsLink = $url . '/robots.txt';
            $sitemapLink = $url . '/sitemap.xml';
            $robots = $this->guzzleGet->guzzlePage($robotsLink);
            if (!$robots[0] != 200 || !strstr(strtolower($robots[3]), 'sitemap')) {
                $crawlLink = $sitemapLink;
            } else {
                $crawlLink = $robotsLink;
            }
        } else {
            $crawlLink = $url;
        }
        $this->log("Crawl link: {$crawlLink}");
        return $this->sitemapParser($crawlLink);
    }

    private function crawlSingleURL($sitemap) {
        foreach ($sitemap['urls'] as $url => $tags) {
            if (in_array($url, $this->excluded)) {
                continue;
            }
            $start = microtime(true);
            $result = $this->guzzleGet->guzzlePage($url);
            $elapsed = microtime(true) - $start;
            if ($elapsed > $this->highestTime) {
                $this->highestTime = ROUND($elapsed, 8);
                $this->highestURL = $url;
            }
            if ($elapsed < $this->lowestTime) {
                $this->lowestTime = ROUND($elapsed, 8);
                $this->lowestURL = $url;
            }
            if ($result[0] > 199 && $result[0] < 300) {
                $this->okURLs++;
            } else {
                $this->badURLs++;
            }
            $this->cnt++;
            $perc = ROUND((100 / $this->total) * $this->cnt);
            $this->log("Crawl URL: {$url} : {$perc}% done");
            sleep($this->sleep);
        }
    }

    /**
     * Crawling of URLs taken from sitemap
     * 
     * @param array $sitemap Array of URLs to crawl one by one
     */
    public function crawlURLS(array $sitemap): bool {
        $this->okURLs = $this->badURLs = $this->cnt = 0;
        $this->start = microtime(true);
        $this->total = count($sitemap['urls']);
        if ($this->total === 0) {
            $this->log("No pages found!");
            die;
        }
        $this->crawlSingleURL($sitemap);
        $this->statistics();

        return true;
    }

    private function statistics() {
        $this->elapsed = round(microtime(true) - $this->start) - ($this->sleep * $this->total);
        $this->rate = ROUND($this->total / $this->elapsed);
        $this->log("Elased: " . $this->elapsed . " seconds, rate $this->rate req/second");
        $this->log("URL stats [ok: $this->okURLs, bad: $this->badURLs]");
        $this->log("Lowest time [$this->lowestURL - $this->lowestTime]");
        $this->log("Highest time [$this->highestURL - $this->highestTime]");
    }

    /**
     * Get crawl statistics
     * 
     * @return array 
     */
    public function getStats(): array {
        return [
            'ok' => $this->okURLs,
            'bad' => $this->badURLs,
            'elapsed' => $this->elapsed,
            'rate' => $this->rate,
            'lowest_time' => $this->lowestTime,
            'lowest_url' => $this->lowestURL,
            'highest_time' => $this->highestTime,
            'highest_url' => $this->highestURL,
        ];
    }

}
