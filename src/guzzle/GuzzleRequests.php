<?php

namespace dr4g0nsr\guzzle;

use \GuzzleHttp\Client as Client;

/**
 * HTTP request sending and handling
 *
 * @author drago
 */
class GuzzleRequests {

    protected $httpClient = NULL;
    protected $verify = false;
    protected $connectTimeout = 5;
    protected $timeout = 10;
    protected $headers = ['Accept-Encoding' => 'gzip, deflate'];

    protected function __construct() {
        $this->httpClient = new Client(
                ['defaults' => [
                'verify' => $this->verify,
                'connect_timeout' => $this->connectTimeout,
                'timeout' => $this->timeout,
            ], 'headers' => $this->headers]
        );
    }

}
