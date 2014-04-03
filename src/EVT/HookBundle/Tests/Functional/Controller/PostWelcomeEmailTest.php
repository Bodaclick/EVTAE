<?php

namespace EVT\HookBundle\Tests\Functional\Controller;

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
                'email' => ['email' => 'valid@email.com'],
                'personal_info' => [
                    'name' => 'testUserName',
                    'surname' => 'testUserSurname',
                    'phone' => '+34 0123456789'
                ]
            ],
            'vertical' => [
                'domain' => 'test.com',
                'lang' => 'es_ES'
            ]
        ];

        $this->client->enableProfiler(); // Enable profiler to get the emails

        $this->client->request(
            'POST',
            '/hooks/welcome/user?apikey=1234',
            [],
            [],
            $this->header,
            json_encode($params)
        );

        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());

        $mailCollector = $this->client->getProfile()->getCollector('swiftmailer');

        // Check that an e-mail was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting e-mail data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals('Bienvenid@ - test.com', $message->getSubject());
        $this->assertEquals('no-reply@test.com', key($message->getFrom()));
        $this->assertEquals('valid@email.com', key($message->getTo()));
        $this->assertContains('testUserName', $message->getBody());
        $this->assertContains('support@test.com', $message->getBody());
        $this->assertContains('Gracias por registrarte', $message->getBody());
    }
}