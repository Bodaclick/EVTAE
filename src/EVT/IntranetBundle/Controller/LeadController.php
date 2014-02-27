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
        $leadsResponse = $this->container->get('evt.core.client')->sendRequest('/api/leads');

        $leads = [];
        if (isset($leadsResponse->getBody()['items'])) {
            $leads = $leadsResponse->getBody()['items'];
        }

        $content = $this->renderView('EVTIntranetBundle:Lists:leads.html.twig', ["leads" => $leads]);

        return new Response($content);
    }
}
