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
}
