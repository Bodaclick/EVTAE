<?php

namespace EVT\HookBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class PostWelcomeManagerEmailTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class PostWelcomeManagerEmailTest extends WebTestCase
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
            "user" => [
                "id" => 1,
                "username" => "username51",
                "username_canonical" => "username51",
                "email" => "email51@email.com",
                "email_canonical" => "email51@email.com",
                "name" => "name51",
                "surnames" => "surnames51",
                "phone" => "0132456789",
                "lang" => "ES_es"
            ],
            "name" => "evt.event.manager_create"
        ];

        $this->client->enableProfiler(); // Enable profiler to get the emails

        $this->client->request(
            'POST',
            '/hooks/welcome/manager?apikey=1234',
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
        $this->assertEquals('no-reply@e-verticals.com', key($message->getFrom()));
        $this->assertEquals('email51@email.com', key($message->getTo()));
    }
}
