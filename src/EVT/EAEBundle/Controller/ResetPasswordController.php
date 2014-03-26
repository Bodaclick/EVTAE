<?php

namespace EVT\EAEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Reset Password
 *
 * @author    Daniel Jimenez <djimenez@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */

class ResetPasswordController extends Controller
{
    /**
     * @Route("/resetpassword", name="resetpassword")
     * @Template()
     */
    public function resetPasswordAction(Request $request)
    {
        $email = $request->request->get('email');
        $arrayContent = $this->container->get('evt.core.client')->get('/api/resets/' . $email . '/password');
        $arrPass = $arrayContent->getBody();

        if (isset($arrPass["passwd"])) {
            $this->mailSend($arrPass["passwd"], $email);
        }

        $content = $this->renderView('EVTEAEBundle:Login:reset_password.html.twig', array('email' => $email));

        return new Response($content);
    }

    private function mailSend($newPassword, $email)
    {
        $data['mailing']['subject'] = 'Change Password';
        $data['mailing']['to'] = $email;
        $data['vertical']['domain'] = 'bodaclick.com';
        $data['mailing']['password'] = $newPassword;

        $this->get('evt.mailer')->send($data, 'EVTEAEBundle:Email:ResetPassword.User.html.twig');
    }
}
