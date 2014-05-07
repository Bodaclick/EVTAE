<?php

namespace EVT\DIYBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        try {
            return $this->get('evt.diy.showroom.manager')->changeName($id, $request->request->get('name'));
        }
        catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    public function descriptionShowroomAction($id, Request $request)
    {
        try {
            return $this->get('evt.diy.showroom.manager')->changeDescription($id, $request->request->get('description'));
        }
        catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    public function toreviewShowroomAction($id)
    {
        try {
            return $this->get('evt.diy.showroom.manager')->toreview($id);
        }
        catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
}
