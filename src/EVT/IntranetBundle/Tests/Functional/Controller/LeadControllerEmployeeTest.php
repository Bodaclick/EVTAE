<?php

namespace EVT\IntranetBundle\Tests\Functional\Controller;

use EVT\CoreClientBundle\Client\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * LeadControllerEmployeeTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class LeadControllerEmployeeTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;

    /**
     * Create a client to test request and mock services
     */
    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @vcr apiLeadsEmployee.yml
     */
    public function testLeads()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/manager/leads');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h3.page-title')->count());
        $this->assertEquals('Leads', $crawler->filter('h3.page-title')->html());
        $this->assertEquals(1, $crawler->filter('table.table')->count());
        $this->assertEquals(7, $crawler->filter('a.green-stripe')->count());
        $this->assertEquals(2, $crawler->filter('a.badge-warning')->count());
    }

    private function logIn()
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
