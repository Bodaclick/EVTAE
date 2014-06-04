<?php

namespace EVT\HookBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostShowroomPublishedManagerEmailTest extends WebTestCase
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
            'showroom' => [
                "id"=> 1,
                "slug"=> "name",
                "name" => 'name',
                "provider"=> [
                    "notification_emails"=> ["validNotification@email.com"],
                    "lang" => "es_ES"
                ],
                "vertical"=> [
                    "domain"=> "test.com",
                ]
            ],
            'url' => 'http://www.testurl.com/slug'
        ];

        $this->client->enableProfiler(); // Enable profiler to get the emails

        $this->client->request(
            'POST',
            '/hooks/showroom/published?apikey=1234',
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
        $this->assertEquals('no-reply@test.com', key($message->getFrom()));
        $this->assertEquals('validNotification@email.com', key($message->getTo()));
        $this->assertEquals('support@test.com', key($message->getCc()));
    }
}
