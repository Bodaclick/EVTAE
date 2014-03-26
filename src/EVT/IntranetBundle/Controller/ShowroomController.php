<?php

namespace EVT\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ShowroomController extends Controller
{
    /**
     * @Route("/showrooms")
     */
    public function listAction()
    {
        $showroomResponse = $this->container->get('evt.core.client')->get('/api/showrooms');

        $showrooms = [];
        if (isset($showroomResponse->getBody()['items'])) {
            $showrooms = $showroomResponse->getBody()['items'];
        }

        $content = $this->renderView('EVTIntranetBundle:Lists:showrooms.html.twig', ["showrooms" => $showrooms]);
        return new Response($content);
    }
}
