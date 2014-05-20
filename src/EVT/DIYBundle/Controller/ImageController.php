<?php

namespace EVT\DIYBundle\Controller;

use FOS\RestBundle\Util\Codes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View as FosView;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * ImageController
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class ImageController extends Controller
{
    public function postImageAction(Request $request)
    {
        $image = $request->files->get('image');

        if (!$image instanceof UploadedFile) {
            throw new BadRequestHttpException('No file');
        }

        $url = $this->container->get('evt.diy.uploader')->upload($image);

        $view = FosView::create();
        $view->setStatusCode(Codes::HTTP_CREATED)
            ->setData(
                ['url' =>  $url]
            );

        return $view;
    }
}
