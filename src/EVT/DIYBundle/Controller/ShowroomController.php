<?php

namespace EVT\DIYBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as FOS;

/**
 * Class ShowroomController
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class ShowroomController extends Controller
{
    /**
     * @FOS\View()
     */
    public function getShowroomAction()
    {
        return [];
    }
}
