<?php

declare(strict_types=1);

namespace dr4g0nsr;

use dr4g0nsr\sitemap\SitemapGet;

/**
 * Main class for sitemap crawl
 *
 * @author dragonmen@gmail.com
 */
class SitemapCrawler extends SitemapGet implements ISitemapCrawler {

    const SC_VERSION = "1.0a";

    private $sleep = 0;
    private $excluded = [];
    
    use TCrawlerBasic;

    /**
     * Inject settings here using array, preferably loaded from config or automated
     * 
     * @param array $settings Basic settings, can be parsed from config
     */
    public function __construct(array $settings = []) {
        parent::__construct();
        $this->checkPrerequisites();
        if (!empty($settings)) {
            $this->settings = $settings;
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
            $robots = $this->guzzlePage($robotsLink);
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

    /**
     * Crawling of URLs taken from sitemap
     * 
     * @param array $sitemap Array of URLs to crawl one by one
     */
    public function crawlURLS(array $sitemap): bool {
        $ok = $bad = $cnt = 0;
        $start = microtime(true);
        $total = count($sitemap['urls']);
        if ($total === 0) {
            $this->log("No pages found!");
            die;
        }
        foreach ($sitemap['urls'] as $url => $tags) {
            if (in_array($url, $this->excluded)) {
                continue;
            }
            $result = $this->guzzlePage($url);
            if ($result[0] > 199 && $result[0] < 300) {
                $ok++;
            } else {
                $bad++;
            }
            $cnt++;
            $perc = ROUND((100 / $total) * $cnt);
            $this->log("Crawl URL: $url : $perc% done");
            sleep($this->sleep);
        }
        $this->log("URL stats [ok: $ok, bad: $bad]");

        $elapsed = round(microtime(true) - $start) - ($this->sleep * $total);
        $rate = ROUND($total / $elapsed);
        $this->log("Elased: " . $elapsed . " seconds, rate $rate req/second");

        return true;
    }

}
