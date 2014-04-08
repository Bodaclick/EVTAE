<?php

namespace EVT\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ShowroomController extends Controller
{
    /**
     * @Route("/showrooms")
     */
    public function listAction(Request $request)
    {
        $showroomResponse = $this->container->get('evt.core.client')
            ->get('/api/showrooms?page='.$request->query->get('page', 1));

        if (404 == $showroomResponse->getStatusCode() && $request->query->get('page', 1)>1) {
            throw new NotFoundHttpException();
        }

        $showrooms = [];
        if (isset($showroomResponse->getBody()['items'])) {
            $showrooms = $showroomResponse->getBody()['items'];
        }

        $pagination = $showroomResponse->getBody()['pagination'];

        $content = $this->renderView(
            'EVTIntranetBundle:Lists:showrooms.html.twig',
            [
                "showrooms" => $showrooms,
                "pagination" => $pagination,
                "routeController" => "evt_intranet_showroom_list"
            ]
        );
        return new Response($content);
    }
}
