<?php

namespace EVT\DIYBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as FOS;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
    public function getShowroomAction(Request $request, $id)
    {
        $this->checkAuth('view', $request);

        return $this->get('evt.diy.showroom.manager')->get($id);
    }

    public function nameShowroomAction(Request $request, $id)
    {
        $this->checkAuth('edit', $request);

        return $this->get('evt.diy.showroom.manager')->changeName($id, $request->request->get('name'));
    }

    public function descriptionShowroomAction(Request $request, $id)
    {
        $this->checkAuth('edit', $request);

        return $this->get('evt.diy.showroom.manager')->changeDescription($id, $request->request->get('description'));
    }

    public function toreviewShowroomAction(Request $request, $id)
    {
        $this->checkAuth('edit', $request);

        return $this->get('evt.diy.showroom.manager')->toreview($id);
    }

    public function publishShowroomAction(Request $request, $id)
    {
        $this->checkAuth('edit', $request);

        return $this->get('evt.diy.showroom.manager')->publish($id);
    }

    private function checkAuth($grantType, Request $request)
    {
        if (!$this->container->get('security.context')->isGranted($grantType, $request->get('_route'))) {
            throw new AccessDeniedException();
        }
    }

    public function activeShowroomEditionAction($id)
    {
        try {
            return $this->get('evt.diy.showroom.manager')->activeEdition($id);
        }
        catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
}
