<?php

namespace EVT\CoreClientBundle\Client;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Exception\ClientErrorResponseException;

/**
 * Client
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class Client
{
    private $guzzleClient;
    private $domain;
    private $securityClient;

    public function __construct(ClientInterface $guzzleClient, $domain, $securityClient)
    {
        $this->guzzleClient = $guzzleClient;
        $this->domain = $domain;
        $this->securityClient = $securityClient;
    }

    public function get($url)
    {
        $request = $this->guzzleClient->get($this->domain .$this->securityClient->securizeUrl($url));
        try {
            $response = $request->send();
        } catch (\Exception $e) {
            return new Response(404, []);
        }
        return $this->securityClient->securizeResponse($response);
    }

    public function patch($url, $body = null)
    {
        $request = $this->guzzleClient->patch($this->domain .$this->securityClient->securizeUrl($url), [], $body);
        try {
            $response = $request->send();
        } catch (\Exception $e) {
            return new Response(404, []);
        }
        return $this->securityClient->securizeResponse($response);
    }

    public function post($url, $toPost = null)
    {
        $request = $this->guzzleClient->post(
            $this->domain .$this->securityClient->securizeUrl($url),
            ['content-type' => 'application/x-www-form-urlencoded'],
            $toPost
        );

        try {
            $response = $request->send();
        } catch (ClientErrorResponseException $e) {
            return new Response($e->getResponse()->getStatusCode(), $e->getResponse()->getMessage());
        } catch (\Exception $e) {
            return new Response(404, []);
        }

        return $this->securityClient->securizeResponse($response);
    }
}
