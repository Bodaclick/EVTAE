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

    use \EVT\IntranetBundle\Tests\Functional\LoginTrait;

    /**
     * Create a client to test request and mock services
     */
    public function setUp()
    {
        $classes = [
            'EVT\DIYBundle\Tests\DataFixtures\ORM\LoadShowroomData'
        ];
        $this->loadFixtures($classes);

        $this->client = static::createClient();
        $this->header = ['HTTP_Accept' => 'application/json'];
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

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->request(
            'GET',
            '/api/showrooms/1',
            [],
            [],
            $this->header
        );
        $showroom = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('newName', $showroom['name']);
    }

    public function testChangeNameKO()
    {
        $this->markTestIncomplete(
            'This test depends everymundo.'
        );
        
        $this->logInEmployee();

        $this->client->request(
            'PATCH',
            '/api/showrooms/1000/name',
            ['name' => 'newName'],
            [],
            $this->header
        );

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

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

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->request(
            'GET',
            '/api/showrooms/1',
            [],
            [],
            $this->header
        );
        $showroom = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('new description', $showroom['description']);
    }

    public function testChangeDescriptionKO()
    {
        $this->markTestIncomplete(
            'This test depends everymundo.'
        );

        $this->logInEmployee();

        $this->client->request(
            'PATCH',
            '/api/showrooms/1000/description',
            ['description' => 'new description'],
            [],
            $this->header
        );

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }
}
