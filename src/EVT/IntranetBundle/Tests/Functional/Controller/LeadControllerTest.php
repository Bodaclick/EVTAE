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
    use \EVT\IntranetBundle\Tests\Functional\LoginTrait;

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
     * @vcr apiLeads.yml
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

    /**
     * @vcr apiLeadsPagination.yml
     */
    public function testLeadsPagination()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/manager/leads?page=2');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h3.page-title')->count());
        $this->assertEquals('Leads', $crawler->filter('h3.page-title')->html());
        $this->assertEquals(1, $crawler->filter('table.table')->count());
        $this->assertEquals(10, $crawler->filter('a.green-stripe')->count());
        $this->assertEquals(2, trim($crawler->filter('li.disabled a')->html()));
    }

    /**
     * @vcr apiLeadsPaginationKo.yml
     */
    public function testLeadsPaginationKo()
    {
        $this->logIn();

        $this->client->request('GET', '/manager/leads?page=3');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @vcr apiLead1.yml
     */
    public function testLead()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/manager/leads/1');

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

        $crawler = $this->client->request('GET', '/manager/leads/1');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h3.page-title')->count());
        $this->assertEquals('Lead Detail', $crawler->filter('h3.page-title')->html());
        $this->assertEquals(1, $crawler->filter('table.table')->count());
        $this->assertEquals(3, $crawler->filter('a.badge-warning')->count());
    }
}
