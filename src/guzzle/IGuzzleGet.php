<?php

namespace dr4g0nsr\guzzle;

/**
 * Description of IGuzzleGet
 *
 * @author drago
 */
interface IGuzzleGet {
    
    public function guzzlePage(string $url): array;
    
}
