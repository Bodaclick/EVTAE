<?php
namespace EVT\IntranetBundle\Tests\Functional;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait LoginTrait
{
    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'admin_secured_area';
        $token = new UsernamePasswordToken('manager', null, $firewall, array('ROLE_MANAGER'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    private function logInEmployee()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'admin_secured_area';
        $token = new UsernamePasswordToken('employee', null, $firewall, array('ROLE_EMPLOYEE'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
