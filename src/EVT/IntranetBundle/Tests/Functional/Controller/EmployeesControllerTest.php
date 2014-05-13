<?php

namespace EVT\IntranetBundle\Tests\Functional\Controller;

use EVT\CoreClientBundle\Client\Response;
use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

 /**
 * EmployeesControllerTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class EmployeesControllerTest extends WebTestCase
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
            ->will($this->returnValue('1'));

        $this->client = static::createClient();
        $this->client->getContainer()->set('snc_redis.auth', $predisClient);
    }

    public function testEmpoyees()
    {
        $this->logInEmployee();

        $crawler = $this->client->request('GET', '/employee/employees/new');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h3.page-title')->count());
        $this->assertEquals('new employee', $crawler->filter('h3.page-title')->html());
    }

    /**
     * @vcr apiEmployeesPost.yml
     */
    public function testEmployeesPostWrongParameters()
    {
        $this->logInEmployee();

        $crawler = $this->client->request('POST', '/employee/employees/new', ['username' => 'asdf']);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('.note-danger')->count());
        $this->assertEquals('Wrong parameters', trim($crawler->filter('.note-danger > p')->html()));
    }

    /**
     * @vcr apiEmployeesPostOk.yml
     */
    public function testEmployeesPostOk()
    {
        $this->markTestSkipped('TODO Manager refactoring');

        $this->logInEmployee();

        $params = [
            'user' => [
                'name' => 'Name',
                'surnames' => 'Surnames',
                'username' => 'newUsername',
                'email' => 'newEmail@email.com',
                'plainPassword' => [
                    'first' => 'pass',
                    'second' => 'pass'
                ]
            ]
        ];

        $crawler = $this->client->request('POST', '/employee/employees/new', $params);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertEquals(0, $crawler->filter('.note-danger')->count());
        $this->assertEquals(1, $crawler->filter('.note-info')->count());
    }
}
