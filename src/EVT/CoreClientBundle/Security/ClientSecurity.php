<?php

namespace EVT\CoreClientBundle\Security;
use EVT\CoreClientBundle\Client\Response;

/**
 * ClientSecurity
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class ClientSecurity
{
    public function securizeUrl($url)
    {
        $securizedUrl = $url;
        return $securizedUrl;
    }

    public function securizeResponse($response)
    {
        return new Response('200', $response->json());
    }
}
