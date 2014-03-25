<?php

namespace EVT\IntranetBundle\Tests\Functional\EventListener;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

 /**
 * LocaleListenerTest
 *
 * @author    Alvaro Prudencio <aprudencio@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class LocaleListenerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;
    protected $session;
    use \EVT\IntranetBundle\Tests\Functional\LoginTrait;

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
            '/manager/leads',
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
            '/manager/leads',
            [],
            [],
            array('HTTP_ACCEPT_LANGUAGE' => 'fr')
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("Leads", $crawler->filter('h3.page-title')->html());
    }

    /**
     * @vcr apiLeads.yml
     */
    public function testRoleExist()
    {
        $this->logIn();
        $this->client->request(
            'GET',
            '/employee/leads',
            [],
            [],
            array('HTTP_ACCEPT_LANGUAGE' => 'es')
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("manager", $this->client->getContainer()->get('session')->get('_role'));
    }
}
