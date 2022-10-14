<?php

declare(strict_types=1);

namespace dr4g0nsr;

/**
 * Main class for sitemap crawl
 *
 * @author dragonmen@gmail.com
 */
class SitemapCrawler {

    const SC_VERSION = "1.0a";

    private $prerequisites = ["curl_init", "mb_language"];
    private $sleep = 0;
    private $excluded = [];
    private $settings = [];
    private $temporarySettings = [];
    private $guzzleURL = NULL;
    private $sitemapGet = NULL;

    /**
     * Inject settings here using array, preferably loaded from config or automated
     * 
     * @param array $settings
     */
    public function __construct(array $settings = []) {
        foreach ($this->prerequisites as $ext) {
            if (!function_exists($ext)) {
                print "Prerequisites failed: $ext\n";
                die;
            }
        }
        if (!empty($settings)) {
            $this->settings = $settings;
        }
        $this->guzzleURL = new GuzzleURL();
        $this->sitemapGet = new SitemapGet();
    }

    /**
     * Static function to check version of class
     * 
     * @return string
     */
    public static function version(): string {
        return self::SC_VERSION;
    }

    /**
     * Loads config and merge current one with loaded
     * 
     * It will override settings injected at constructor or default ones if constructor didn't use any settings
     * 
     * @param string $path Path to config.php, if ommited it will use default one from path where tests are, which is probably not what you want
     * @throws \Exception Exception if config does not exists
     */
    public function loadConfig($path = NULL) {
        if (empty($path)) {
            $path = __DIR__ . '/../config.php';
        }
        if (!file_exists($path)) {
            throw new \Exception('Config does not exists');
        }
        require($path);
        foreach ($settings as $setting => $val) {
            $this->temporarySettings[$setting] = $val;
        }
        $this->settings = array_merge($this->temporarySettings, $this->settings);
    }

    /**
     * Return settings stored internally
     * 
     * @return type
     */
    public function getSettings() {
        return $this->settings;
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
            $robots = $this->guzzleURL->guzzlePage($robotsLink);
            if (!$robots[0] != 200 || !strstr(strtolower($robots[3]), 'sitemap')) {
                $crawlLink = $sitemapLink;
            } else {
                $crawlLink = $robotsLink;
            }
        } else {
            $crawlLink = $url;
        }
        $this->log("Crawl link: {$crawlLink}");
        return $this->sitemapGet->sitemapParser($crawlLink);
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
            $result = $this->guzzleURL->guzzlePage($url);
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

    /**
     * Add message to log
     * 
     * @param mixed $message Message to add to log
     */
    private function log($message) {
        print $message . PHP_EOL;
    }

}
