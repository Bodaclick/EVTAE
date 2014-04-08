<?php

namespace EVT\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ManagersController extends Controller
{
    /**
     * @Route("/managers")
     */
    public function listAction(Request $request)
    {
        $managersResponse = $this->container->get('evt.core.client')
            ->get('/api/managers?page='.$request->query->get('page', 1));

        if (404 == $managersResponse->getStatusCode() && $request->query->get('page', 1)>1) {
            throw new NotFoundHttpException();
        }

        $managers = [];
        if (isset($managersResponse->getBody()['items'])) {
            $managers = $managersResponse->getBody()['items'];
        }

        $pagination = $managersResponse->getBody()['pagination'];

        $content = $this->renderView(
            'EVTIntranetBundle:Lists:managers.html.twig',
            [
                "managers" => $managers,
                "pagination" => $pagination,
                "routeController" => "evt_intranet_managers_list"
            ]
        );
        return new Response($content);
    }
}
