<?php

namespace EVT\HookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class UserWelcomeHookController
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
@copyright 2014 Bodaclick */
class WelcomeHookController  extends Controller
{
    /**
     * @Method("POST")
     * @Route("welcome/user")
     */
    public function postWelcomeUserAction(Request $request)
    {
        var_dump($this->container->getParameter('aws_key'));
        $domain = $request->request->get('vertical')['domain'];
        $this->get('evt.mailer.aws_welcome')->send(
            $request->request->all(),
            'EVTEAEBundle:Email:Welcome.' . $domain . '.html.twig'
        );
        $response = new JsonResponse();
        return $response->setStatusCode(202);
    }

} 