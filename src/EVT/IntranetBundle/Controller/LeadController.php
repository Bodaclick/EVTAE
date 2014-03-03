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
        $leadsResponse = $this->container->get('evt.core.client')->get('/api/leads');

        $leads = [];
        if (isset($leadsResponse->getBody()['items'])) {
            $leads = $leadsResponse->getBody()['items'];
        }

        $content = $this->renderView('EVTIntranetBundle:Lists:leads.html.twig', ["leads" => $leads]);

        return new Response($content);
    }

    /**
     * @Route("/leads/{id}", requirements={"id" = "\d+"})
     */
    public function showLeadAction($id)
    {
        $leadResponse = $this->container->get('evt.core.client')->get('/api/leads/'.$id);
        $lead = $leadResponse->getBody();

        $content = $this->renderView('EVTIntranetBundle:Lists:lead.html.twig', ["lead" => $lead]);
        if (!isset($lead['read_at'])) {
            $leadResponse = $this->container->get('evt.core.client')->patch('/api/leads/'.$id.'/read');
        }

        return new Response($content);
    }
}
