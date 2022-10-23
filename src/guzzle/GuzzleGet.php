<?php

namespace dr4g0nsr\guzzle;

/**
 * HTTP request sending and handling
 *
 * @author drago
 */
class GuzzleGet extends GuzzleRequests implements IGuzzleGet {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Use guzzle to get page
     * 
     * Returns four values as the result of operation:
     * code - should be 200 if everything is normal, it refers to http code
     * type - actual content-type from response
     * body - body of response
     * bodyraw - original body of response with not filters and stuff
     * 
     * @param string $url URL to get, can be http or https, ssl/tls is not checked for validity, self-signed will work
     * @return array Returning array of 4 values from executing request: code, type, body and raw body
     */
    public function guzzlePage(string $url): array {
        try {
            $response = $this->httpClient->request('GET', $url);
        } catch (Exception $e) {
            // No exception
        }
        $code = $response->getStatusCode(); // 200
        $type = $response->getHeaderLine('content-type'); // 'application/json; charset=utf8'
        $body = $response->getBody(); // '{"id": 1420053, "name": "guzzle", ...}'
        $bodyRaw = $body->getContents();

        return [$code, $type, $body, $bodyRaw];
    }

}
