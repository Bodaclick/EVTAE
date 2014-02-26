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

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function securizeUrl($url)
    {
        $this->context = $this->container->get('security.context');
        $securizedUrl = $url;
        $token = $this->context->getToken();
        if (null === $token) {
            return $url;
        }
        
        $username = $token->getUsername();

        if (false !== strpos($securizedUrl, '?')) {
            $securizedUrl .= '&canView='. $username;
        } else {
            $securizedUrl .= '?canView='. $username;
        }

        return $securizedUrl;
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
