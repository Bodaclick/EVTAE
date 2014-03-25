<?php

namespace EVT\IntranetBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'page-sidebar-menu');

        /*
         * Activate only when usable
         * $menu->addChild('Dashboard', array('route' => 'evt_intranet_home_index'))->setAttribute('icon', 'fa-home');
         */
        $menu->addChild(
            $this->container->get('translator')->trans('leads'),
            [
                'route' => 'evt_intranet_lead_list',
                'routeParameters' => ['_role' => $this->container->get('session')->get('_role')]
            ]
        )->setAttribute('icon', 'fa-exchange');
        $menu->addChild(
            $this->container->get('translator')->trans('showrooms'),
            [
                'route' => 'evt_intranet_showroom_list',
                'routeParameters' => ['_role' => $this->container->get('session')->get('_role')]
            ]
        )->setAttribute('icon', 'fa-ticket');

        return $menu;
    }
}
