<?php

namespace EVT\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class LeadController extends Controller
{
    /**
     * @Route("/leads")
     */
    public function listAction()
    {
        $leads = $this->container->get('evt.core.client')->sendRequest('/api/leads');
        $content = $this->renderView(
            'EVTIntranetBundle:Lists:leads.html.twig',
            ["leads" => $leads->getBody()['items']]
        );

        return new Response($content);
    }
}
