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
        $from_date = '2010-01-01';
        $to_date = '2020-01-01';

        $leadsResponse = $this->container->get('evt.core.client')
            ->get('/stats/leads?from_date='.$from_date.'&to_date='.$to_date);

        $statsLeads['from_date'] = $from_date;
        $statsLeads['to_date'] = $to_date;
        $statsLeads['data'] = json_encode($leadsResponse->getBody());
        $content = $this->renderView(
            'EVTIntranetBundle:Stats:index.html.twig',
            ['statsLeads' => $statsLeads]
        );
        return new Response($content);
    }
}
