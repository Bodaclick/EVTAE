<?php

namespace EVT\HookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class UserWelcomeHookController
 * @author    Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class EmailHookController extends Controller
{
    /**
     * @Method("POST")
     * @Route("welcome/user")
     */
    public function postWelcomeUserAction(Request $request)
    {
        $data = [];
        $content = $this->get("request")->getContent();
        if (!empty($content)) {
            $data = json_decode($content, true); // 2nd param to get as array
        }
        $data['subject'] = $this->get('translator')->trans('user.welcome.subject', [], 'email', 'es_ES');

        $domain = $data['vertical']['domain'];
        $this->get('evt.mailer')->send($data, 'EVTEAEBundle:Email:Welcome.' . $domain . '.html.twig');
        $response = new JsonResponse();
        return $response->setStatusCode(202);
    }

    /**
     * @Method("POST")
     * @Route("lead/user")
     */
    public function postLeadUserAction(Request $request)
    {
        $data = [];
        $content = $this->get("request")->getContent();
        if (!empty($content)) {
            $data = json_decode($content, true); // 2nd param to get as array
        }
        $data['subject'] = $this->get('translator')->trans('user.lead.subject', [], 'email', 'es_ES');
        $data['vertical'] = $data['showroom']['vertical'];
        $data['user']['email'] = $data['email']['email'];

        $domain = $request->request->get('vertical')['domain'];
        $this->get('evt.mailer')->send($data, 'EVTEAEBundle:Email:Lead.User' . $domain . '.html.twig');
        $response = new JsonResponse();
        return $response->setStatusCode(202);
    }
}
