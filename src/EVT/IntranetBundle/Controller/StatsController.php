<?php

namespace EVT\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatsController extends Controller
{
    /**
     * @Route("/stats/")
     */
    public function indexAction(Request $request)
    {
        $from_date = $request->query->get('create_start');
        $to_date = $request->query->get('create_end');

        if (empty($from_date)) {
            $from_date = date('Y-m-d', strtotime("-30 days"));
        }

        if (empty($to_date)) {
            $to_date = date('Y-m-d');
        }

        $verticalsResponse = $this->container->get('evt.core.client')
            ->get('/api/verticals');

        $leadsResponse = $this->container->get('evt.core.client')
            ->get('/stats/leads?from_date='.$from_date.'&to_date='.$to_date);

        $statsLeads['from_date'] = $from_date;
        $statsLeads['to_date'] = $to_date;
        $statsLeads['data'] = json_encode($leadsResponse->getBody());
        $content = $this->renderView(
            'EVTIntranetBundle:Stats:index.html.twig',
            [
                'statsLeads' => $statsLeads,
                'verticals' => $verticalsResponse->getBody()
            ]
        );
        return new Response($content);
    }
}
