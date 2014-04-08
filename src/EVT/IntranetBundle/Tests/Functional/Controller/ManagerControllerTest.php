<?php

namespace EVT\IntranetBundle\Tests\Functional\Controller;

use EVT\CoreClientBundle\Client\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

 /**
 * ManagerControllerTest
 *
 * @author    Alvaro Prudencio <aprudencio@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class ManagerControllerTest extends WebTestCase
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
     * @vcr apiManagers.yml
     */
    public function testManagers()
    {
        $this->logInEmployee();

        $crawler = $this->client->request('GET', '/employee/managers');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h3.page-title')->count());
        $this->assertEquals('Managers', $crawler->filter('h3.page-title')->html());
        $this->assertEquals(1, $crawler->filter('table.table')->count());
    }
}
