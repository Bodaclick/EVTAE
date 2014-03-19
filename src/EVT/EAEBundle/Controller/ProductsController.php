<?php

namespace EVT\EAEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends Controller
{
    /**
     * @Route("/products")
     */
    public function indexAction()
    {
        $content = $this->renderView('EVTEAEBundle::products.html.twig');
        return new Response($content);
    }
}
