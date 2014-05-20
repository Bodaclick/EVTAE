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
        $filter = '';
        foreach ($request->query as $key => $param) {
            if ($param != '') {
                $filter .= '&' . $key . '=' . $param;
            }
        }

        $showroomResponse = $this->container->get('evt.core.client')
            ->get('/api/showrooms?'. $filter);

        if (404 == $showroomResponse->getStatusCode() && $request->query->get('page', 1)>1) {
            throw new NotFoundHttpException();
        }

        $showrooms = [];
        if (isset($showroomResponse->getBody()['items'])) {
            $showrooms = $showroomResponse->getBody()['items'];
        }

        $pagination = null;
        if (isset($showroomResponse->getBody()['pagination'])){
            $pagination = $showroomResponse->getBody()['pagination'];
        }


        $verticalsResponse = $this->container->get('evt.core.client')
            ->get('/api/verticals');

        $content = $this->renderView(
            'EVTIntranetBundle:Lists:showrooms.html.twig',
            [
                "showrooms" => $showrooms,
                "pagination" => $pagination,
                "routeController" => "evt_intranet_showroom_list",
                "verticals" => $verticalsResponse->getBody()
            ]
        );
        return new Response($content);
    }
}
