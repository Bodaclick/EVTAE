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
                    'username' => 'testUsername',
                    'name' => 'testUserName',
                    'surname' => 'testUserSurname',
                    'email' => 'valid@email.com',
                    'phone' => '+34 0123456789'
                ],
                'vertical' => [
                    'domain' => 'fiestaclick.mx'
                ]
        ];

        $this->client->request(
            'POST',
            '/hooks/welcome/user?apikey=1234',
            $params,
            [],
            $this->header
        );

        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());
    }
}
 