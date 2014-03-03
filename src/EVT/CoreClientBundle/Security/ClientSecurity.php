<?php

namespace EVT\CoreClientBundle\Security;

use EVT\CoreClientBundle\Client\Response;
use Guzzle\Http\Message\Response as GuzzleResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * ClientSecurity
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class ClientSecurity
{

    protected $container;
    protected $context;
    protected $apikey;

    public function __construct(ContainerInterface $container, $apikey)
    {
        $this->container = $container;
        $this->apikey = $apikey;
    }

    public function securizeUrl($url)
    {
        $this->context = $this->container->get('security.context');
        $securizedUrl = $url;

        if (false !== strpos($securizedUrl, '?')) {
            $securizedUrl .= '&apikey='. $this->apikey;
        } else {
            $securizedUrl .= '?apikey='. $this->apikey;
        }

        $token = $this->context->getToken();
        if (null === $token) {
            return $securizedUrl;
        } else {
            return $securizedUrl .'&canView=' .$token->getUsername();
        }
    }

    public function securizeResponse(GuzzleResponse $response)
    {
        // $this->context = $this->container->get('security.context');
        // $username = $this->context->getToken()->getUsername();
        // $roles = $this->context->getToken()->getRoles();
        // $unsetters = $this->getUnsetters($roles);
        // $respArray = $response->json();

        return new Response($response->getStatusCode(), $response->json());
    }

    private function getUnsetters($roles)
    {
        return "obj > removeMe";
    }
}
