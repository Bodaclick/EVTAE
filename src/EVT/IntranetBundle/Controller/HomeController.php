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

        $content = $this->renderView(
            'EVTIntranetBundle:Lists:index.html.twig'
        );

        return new Response($content);

        //return new Response('Hello world!');
    }
}
