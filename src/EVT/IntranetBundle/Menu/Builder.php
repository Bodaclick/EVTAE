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

        $menu->addChild('Dashboard', array('route' => 'evt_intranet_home_index'))->setAttribute('icon', 'fa-home');
        $menu->addChild('Leads', array(
            'route' => 'evt_intranet_lead_list'
        ))->setAttribute('icon', 'fa-exchange');

        return $menu;
    }
}