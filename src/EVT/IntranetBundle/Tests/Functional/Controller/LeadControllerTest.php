<?php

namespace EVT\IntranetBundle\Tests\Functional\Controller;

use EVT\CoreClientBundle\Client\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

 /**
 * LeadControllerTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class LeadControllerTest extends WebTestCase
{

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;

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
    public function testLeads()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/leads');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h3.page-title')->count());
        $this->assertEquals('Leads', $crawler->filter('h3.page-title')->html());
        $this->assertEquals(1, $crawler->filter('table.table')->count());
        $this->assertEquals(7, $crawler->filter('a.green-stripe')->count());
        $this->assertEquals(2, $crawler->filter('a.badge-warning')->count());
    }

    /**
     * @vcr apiLead1.yml
     */
    public function testLead()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/leads/1');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h3.page-title')->count());
        $this->assertEquals('Lead Detail', $crawler->filter('h3.page-title')->html());
        $this->assertEquals(1, $crawler->filter('table.table')->count());
        $this->assertEquals(0, $crawler->filter('a.badge-warning')->count());
    }

    /**
     * @vcr apiLeadFree.yml
     */
    public function testLeadFree()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/leads/1');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h3.page-title')->count());
        $this->assertEquals('Lead Detail', $crawler->filter('h3.page-title')->html());
        $this->assertEquals(1, $crawler->filter('table.table')->count());
        $this->assertEquals(3, $crawler->filter('a.badge-warning')->count());
    }
}
