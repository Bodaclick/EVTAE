<?php

namespace EVT\IntranetBundle\Test\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

 /**
 * LocaleControllerTest
 *
 * @author    Alvaro Prudencio <aprudencio@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class LocaleControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;
    protected $session;

    /**
     * Create a client to test request and mock services
     */
    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @vcr apiLeads.yml
     */
    public function testLocaleExist()
    {
        $this->logIn();
        $crawler = $this->client->request(
            'GET',
            '/leads',
            [],
            [],
            array('HTTP_ACCEPT_LANGUAGE' => 'es')
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("Altas", $crawler->filter('h3.page-title')->html());
    }

    /**
     * @vcr apiLeads.yml
     */
    public function testLocaleNoExist()
    {
        $this->logIn();
        $crawler = $this->client->request(
            'GET',
            '/leads',
            [],
            [],
            array('HTTP_ACCEPT_LANGUAGE' => 'fr')
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("Leads", $crawler->filter('h3.page-title')->html());
    }

    private function logIn()
    {
        $this->session = $this->client->getContainer()->get('session');

        $firewall = 'admin_secured_area';
        $token = new UsernamePasswordToken('manager', null, $firewall, array('ROLE_MANAGER'));
        $this->session->set('_security_'.$firewall, serialize($token));
        $this->session->save();

        $cookie = new Cookie($this->session->getName(), $this->session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}