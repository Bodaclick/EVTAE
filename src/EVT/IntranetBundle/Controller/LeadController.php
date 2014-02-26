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
        $content = $this->renderView('EVTIntranetBundle:Lists:leads.html.twig');

        return new Response($content);
    }
}
