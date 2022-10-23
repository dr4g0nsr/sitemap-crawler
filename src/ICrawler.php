<?php

namespace dr4g0nsr;

/**
 * Description of ISitemapCrawler
 *
 * @author drago
 */
interface  ICrawler {
    
    public function getSitemap(string $url): array;
    
    public function crawlURLS(array $sitemap): bool;
    
    public function loadConfig($path = NULL);
    
    public function getStats(): array;
    
}
