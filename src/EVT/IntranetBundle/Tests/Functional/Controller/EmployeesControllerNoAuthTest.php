<?php

namespace EVT\IntranetBundle\Tests\Functional\Controller;

use EVT\CoreClientBundle\Client\Response;
use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

 /**
 * EmployeesControllerNoAuthTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class EmployeesControllerNoAuthTest extends WebTestCase
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
        $predisClient = $this->getMockBuilder('Predis\Client')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $predisClient->expects($this->atLeastOnce())
            ->method('get')
            ->will($this->returnValue(null));

        $this->client = static::createClient();
        $this->client->getContainer()->set('snc_redis.auth', $predisClient);
    }

    public function testEmployees()
    {
        $this->logInEmployee();

        $this->client->request('GET', '/employee/employees/new');

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }
}
