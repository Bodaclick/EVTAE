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
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
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
        $content = $request->getContent();
        if (!empty($content)) {
            $data = json_decode($content, true);
        }
        $request->setLocale($data['vertical']['lang']);

        $data['mailing']['subject'] = $this->get('translator')
            ->trans('title.welcome.user', [], 'messages', $data['vertical']['lang']);
        $data['mailing']['to'] = $data['user']['email']['email'];

        $domain = $data['vertical']['domain'];
        $this->get('evt.mailer')->send($data, 'EVTEAEBundle:Email:Welcome.User.html.twig');
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
        $content = $request->getContent();
        if (!empty($content)) {
            $data = json_decode($content, true);
        }
        $request->setLocale($data['showroom']['vertical']['lang']);

        $data['mailing']['subject'] = $this->get('translator')
            ->trans('title.new.lead.user', [], 'messages', $data['showroom']['vertical']['lang']);
        $data['vertical'] = $data['showroom']['vertical'];
        $data['mailing']['to'] = $data['email']['email'];

        $domain = $data['vertical']['domain'];
        $this->get('evt.mailer')->send($data, 'EVTEAEBundle:Email:Lead.User.html.twig');
        $response = new JsonResponse();
        return $response->setStatusCode(202);
    }

    /**
     * @Method("POST")
     * @Route("lead/manager")
     */
    public function postLeadManagerAction(Request $request)
    {
        $data = [];
        $content = $request->getContent();
        if (!empty($content)) {
            $data = json_decode($content, true);
        }
        $request->setLocale($data['showroom']['provider']['lang']);

        $data['vertical'] = $data['showroom']['vertical'];
        $domain = $data['vertical']['domain'];

        $data['mailing']['subject'] = $this->get('translator')
            ->trans('title.new.lead.manager', [], 'messages', $data['showroom']['provider']['lang']);
        $data['mailing']['to'] = $data['showroom']['provider']['notification_emails'];
        $data['mailing']['cc'] = 'support@'. $domain;

        $this->get('evt.mailer')->send($data, 'EVTEAEBundle:Email:Lead.Manager.html.twig');
        $response = new JsonResponse();
        return $response->setStatusCode(202);
    }

    /**
     * @Method("POST")
     * @Route("showroom/published")
     */
    public function postShowroomPublishedAction(Request $request)
    {
        $data = [];
        $content = $request->getContent();
        if (!empty($content)) {
            $data = json_decode($content, true);
        }

        $request->setLocale($data['showroom']['provider']['lang']);

        $data['vertical'] = $data['showroom']['vertical'];
        $domain = $data['vertical']['domain'];

        $data['mailing']['subject'] = $this->get('translator')
            ->trans('title.new.lead.manager', [], 'messages', $data['showroom']['provider']['lang']);
        $data['mailing']['to'] = $data['showroom']['provider']['notification_emails'];
        $data['mailing']['cc'] = 'support@'. $domain;

        $this->get('evt.mailer')->send($data, 'EVTEAEBundle:Email:Showroom.Published.Manager.html.twig');
        $response = new JsonResponse();
        return $response->setStatusCode(202);
    }
}
