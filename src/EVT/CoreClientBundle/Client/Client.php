<?php

namespace EVT\CoreClientBundle\Client;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Common\Exception\RuntimeException;

/**
 * Client
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class Client {

    private $guzzleClient;
    private $apikey;
    private $domain;

    public function __construct(ClientInterface $guzzleClient, $apikey, $domain)
    {
        $this->guzzleClient = $guzzleClient;
        $this->apikey = $apikey;
        $this->domain = $domain;
    }

    public function sendRequest($url)
    {
        if (false !== strpos($url, '?')) {
            $url .= '&';
        } else {
            $url .= '?';
        }
        $request = $this->guzzleClient->get($this->domain . $url . 'apikey=' . $this->apikey);
        $response = $request->send();

        return $response->json();
    }
}
