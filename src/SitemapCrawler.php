<?php

declare(strict_types=1);

namespace dragutin;

use \GuzzleHttp as http;
use vipnytt\SitemapParser;
use vipnytt\SitemapParser\Exceptions\SitemapParserException;

/**
 * Main class for sitemap crawl
 *
 * @author dragonmen@gmail.com
 */
class SitemapCrawler {

    const SC_VERSION = "1.0a";

    private $httpClient = NULL;
    private $prerequisites = ["curl_init", "mb_language"];
    private $sleep = 0;
    private $requestRate = 0;
    private $excluded = [];

    public function __construct($settings = NULL) {
        foreach ($this->prerequisites as $ext) {
            if (!function_exists($ext)) {
                print "Prerequisites failed: $ext\n";
                die;
            }
        }
    }

    public function loadConfig($path = NULL) {
        if (empty($path)) {
            $path = __DIR__ . '/../config.php';
        }
        if (!file_exists($path)) {
            throw new \Exception('Config does not exists');
        }
        require($path);
        foreach ($settings as $setting => $val) {
            $this->$setting = $val;
        }
    }

    public static function version() {
        return self::SC_VERSION;
    }

    private function guzzlePage($url) {
        $this->httpClient = new \GuzzleHttp\Client();
        try {
            $response = $this->httpClient->request('GET', $url);
        } catch (Exception $e) {
            
        }
        $code = $response->getStatusCode(); // 200
        $type = $response->getHeaderLine('content-type'); // 'application/json; charset=utf8'
        $body = $response->getBody(); // '{"id": 1420053, "name": "guzzle", ...}'
        $bodyRaw = $body->getContents();

        return [$code, $type, $body, $bodyRaw];
    }

    private function sitemapParser($url) {
        $parser = new SitemapParser('MyCustomUserAgent');
        $parser->parseRecursive($url);
        $sitemaps = [];
        foreach ($parser->getSitemaps() as $url => $tags) {
            $sitemaps[$url] = $tags;
        }
        $urls = [];
        foreach ($parser->getURLs() as $url => $tags) {
            $urls[$url] = $tags;
        }
        return ['robots' => $sitemaps, "urls" => $urls];
    }

    public function getSitemap($url) {
        $robotsLink = $url . '/robots.txt';
        $this->log("Robots: {$robotsLink}");
        /** @var SitemapParser $sitemap */
        return $this->sitemapParser($robotsLink);
    }

    public function crawlURLS($sitemap) {
        $ok = $bad = $cnt = 0;
        $start = microtime(true);
        $total = count($sitemap['urls']);
        if ($total===0) {
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
    }

    private function log($message) {
        print $message . PHP_EOL;
    }

}
