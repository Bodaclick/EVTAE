<?php

namespace EVT\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EmployeesController extends Controller
{
    /**
     * @Route("/employees/new")
     */
    public function newAction(Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('view', $request->get('_route'))) {
            throw new AccessDeniedException();
        }

        $errors = '';

        if ($request->getRealMethod() == "POST") {
            $response = $this->container->get('evt.core.client')
                ->post('/api/employees', $request->request->all());

            switch ($response->getStatusCode()) {
                case 201:
                    $content = $this->renderView(
                        'EVTIntranetBundle:Forms:employee_ok.html.twig',
                        ['req' => $request->request->all()]
                    );
                    return new Response($content);
                    break;
                case 400:
                    $errors = 'Wrong parameters';
                    break;
                case 409:
                    $errors = 'Email or username already in use';
                    break;
            }


        }

        $content = $this->renderView(
            'EVTIntranetBundle:Forms:employee.html.twig',
            ['errors' => $errors, 'req' => $request->request->all()]
        );
        return new Response($content);
    }
}
