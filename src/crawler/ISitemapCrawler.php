<?php

namespace dr4g0nsr;

/**
 * Description of ISitemapCrawler
 *
 * @author drago
 */
interface  ISitemapCrawler {
    
    public function getSitemap(string $url): array;
    
    public function crawlURLS(array $sitemap): bool;
    
}
