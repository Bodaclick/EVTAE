<?php

namespace EVT\DIYBundle\Tests\Functional\Controller;

use EVT\DIYBundle\Entity\Showroom;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class ShowroomControllerTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class ShowroomControllerTest extends WebTestCase
{
    protected $client;
    protected $header;
    protected $predisClient;

    use \EVT\IntranetBundle\Tests\Functional\LoginTrait;

    /**
     * Create a client to test request and mock services
     */
    public function setUp()
    {
        $this->predisClient = $this->getMockBuilder('Predis\Client')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $this->predisClient->expects($this->atLeastOnce())
            ->method('get')
            ->will($this->returnValue('1'));

        $classes = [
            'EVT\DIYBundle\Tests\DataFixtures\ORM\LoadShowroomData'
        ];
        $this->loadFixtures($classes);

        $this->client = static::createClient();
        $this->header = ['HTTP_Accept' => 'application/json'];
        $this->client->getContainer()->set('snc_redis.auth', $this->predisClient);
    }

    /**
     * @vcr apiShowroom.yml
     */
    public function testShowroom()
    {
        $this->logInEmployee();

        $this->client->request(
            'GET',
            '/api/showrooms/1',
            [],
            [],
            $this->header
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $showroom = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('evt_id', $showroom);
        $this->assertArrayHasKey('name', $showroom);
        $this->assertArrayHasKey('state', $showroom);

        $this->assertEquals('1', $showroom['evt_id']);
        $this->assertEquals(Showroom::RETRIVED, $showroom['state']);
    }

    /**
     * @vcr apiShowroom.yml
     */
    public function testChangeNameOK()
    {
        $this->logInEmployee();

        $this->client->request(
            'PATCH',
            '/api/showrooms/1/name',
            ['name' => 'newName'],
            [],
            $this->header
        );

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());

        $this->reinitRedis();

        $this->client->request(
            'GET',
            '/api/showrooms/1',
            [],
            [],
            $this->header
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $showroom = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('newName', $showroom['name']);
    }

    public function testChangeNameKO()
    {
        $this->logInEmployee();

        $this->client->request(
            'PATCH',
            '/api/showrooms/1000/name',
            ['name' => 'newName'],
            [],
            $this->header
        );

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @vcr apiShowroom.yml
     */
    public function testChangeDescriptionOK()
    {
        $this->logInEmployee();

        $this->client->request(
            'PATCH',
            '/api/showrooms/1/description',
            ['description' => 'new description'],
            [],
            $this->header
        );

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());

        $this->reinitRedis();

        $this->client->request(
            'GET',
            '/api/showrooms/1',
            [],
            [],
            $this->header
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $showroom = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('new description', $showroom['description']);
    }

    public function testChangeDescriptionKO()
    {
        $this->logInEmployee();

        $this->client->request(
            'PATCH',
            '/api/showrooms/1000/description',
            ['description' => 'new description'],
            [],
            $this->header
        );

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @vcr apiShowroom.yml
     */
    public function testPublishShowroom()
    {
        $this->logInEmployee();

        $this->client->request(
            'PATCH',
            '/api/showrooms/1/toreview',
            [],
            [],
            $this->header
        );

        $this->reinitRedis();

        $this->client->request(
            'PATCH',
            '/api/showrooms/1/publish',
            [],
            [],
            $this->header
        );

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Re-init predis mock. If not executed the second call in a test do not use the mock 8)
     */
    private function reinitRedis()
    {
        $this->client = static::createClient();
        $this->client->getContainer()->set('snc_redis.auth', $this->predisClient);
        $this->logInEmployee();
    }
}
