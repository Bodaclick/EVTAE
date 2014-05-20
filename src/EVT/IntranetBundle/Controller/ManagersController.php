<?php

namespace EVT\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ManagersController extends Controller
{
    /**
     * @Route("/managers")
     */
    public function listAction(Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('view', $request->get('_route'))) {
            throw new AccessDeniedException();
        }

        $filter = '';
        foreach ($request->query as $key => $param) {
            if ($param != '') {
                $filter .= '&' . $key . '=' . $param;
            }
        }

        $managersResponse = $this->container->get('evt.core.client')
            ->get('/api/managers?page='.$request->query->get('page', 1).$filter);


        if (404 == $managersResponse->getStatusCode() && $request->query->get('page', 1)>1) {
            throw new NotFoundHttpException();
        }


        $managers = [];
        if (isset($managersResponse->getBody()['items'])) {
            $managers = $managersResponse->getBody()['items'];
        }

        $pagination = null;
        if (isset($managersResponse->getBody()['pagination'])){
            $pagination = $managersResponse->getBody()['pagination'];
        }



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
