<?php

namespace EVT\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl(
                'evt_intranet_lead_list',
                ['_role' => $this->get('session')->get('_role')]
            )
        );
        /*
         * Activate only when usable
         * $content = $this->renderView('EVTIntranetBundle:Lists:index.html.twig');
         * return new Response($content);
         */
    }
}
