<?php

namespace EVT\HookBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class PostWelcomeEmail
 * @author    Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class PostWelcomeEmailTest extends WebTestCase
{

    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/json'];
    }

    public function testReceiveHook()
    {
        $params = [
            'user' => [
                'personal_info' => [
                    'name' => 'testUserName',
                    'surname' => 'testUserSurname',
                    'phone' => '+34 0123456789'
                ],
                'username' => 'testUsername',
                'email' => 'valid@email.com',
            ],
            'vertical' => [
                'domain' => 'fiestaclick.mx'
            ]
        ];

        $this->client->request(
            'POST',
            '/hooks/welcome/user?apikey=1234',
            [],
            [],
            $this->header,
            json_encode($params)
        );

        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());
    }
}
