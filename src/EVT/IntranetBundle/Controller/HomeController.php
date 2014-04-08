<?php

namespace EVT\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $from_date = '2010-01-01';
        $to_date = '2020-01-01';

        $leadsResponse = $this->container->get('evt.core.client')
            ->get('/stats/leads?from_date='.$from_date.'&to_date='.$to_date);

        $statsLeads['tot'] = 0;
        $statsLeads['from_date'] = $from_date;
        $statsLeads['to_date'] = $to_date;
        foreach ($leadsResponse->getBody() as $lead) {
            $statsLeads['tot'] += $lead['number'];
        }

        $content = $this->renderView('EVTIntranetBundle:Lists:index.html.twig', ['statsLeads' => $statsLeads]);
        return new Response($content);
    }
}
