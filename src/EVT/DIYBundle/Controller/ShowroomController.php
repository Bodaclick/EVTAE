<?php

namespace EVT\DIYBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;

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
    public function getShowroomAction($id)
    {
        return $this->get('evt.diy.showroom.manager')->get($id);
    }

    public function nameShowroomAction($id, Request $request)
    {
        $view = new View('{}', Codes::HTTP_OK);

        try {
            $this->get('evt.diy.showroom.manager')->changeName($id, $request->request->get('name'));
        }
        catch (\Exception $e) {
            $view->setStatusCode(Codes::HTTP_NOT_FOUND);
            return $view;
        }

        return $view;
    }

    public function descriptionShowroomAction($id, Request $request)
    {
        $view = new View('{}', Codes::HTTP_OK);

        try {
            $this->get('evt.diy.showroom.manager')->changeDescription($id, $request->request->get('description'));
        }
        catch (\Exception $e) {
            $view->setStatusCode(Codes::HTTP_NOT_FOUND);
            return $view;
        }

        return $view;
    }
    public function toreviewShowroomAction($id)
    {
        $view = new View('{}', Codes::HTTP_OK);

        try {
            $this->get('evt.diy.showroom.manager')->toreview($id);
        }
        catch (\Exception $e) {
            $view->setStatusCode(Codes::HTTP_NOT_FOUND);
            return $view;
        }

        return $view;
    }
}
