<?php

namespace EVT\HookBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostManagerLeadEmailTest extends WebTestCase
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
            'event' => [
                'date' => '2015-12-31T00:00:00+0000',
                'type' => ['type' => 1, 'name' => 'BIRTHDAY'],
                'location' => [
                    "lat"=> 10,
                    "long" => 10,
                    "admin_level1" => "Getafe",
                    "admin_level2" => "Madrid",
                    "country" => "Spain"
                ]
            ],
            'personal_info' => [
                'name' => 'testUserName',
                'surnames' => 'testUserSurname',
                'email' => 'valid@email.com',
                'phone' => '+34 0123456789'
            ],
            'showroom' => [
                "slug"=> "name",
                "score"=> 0,
                "type"=>[
                    "type"=>2
                ],
                "name" => 'name',
                "phone" => '1234-call-me',
                "provider"=> [
                    "id"=> "1",
                    "name"=> "name1",
                    "slug"=> "name1",
                    "notification_emails"=> ["validNotification@email.com"],
                    "managers"=> [],
                    "location"=> [
                        "lat"=> 10,
                        "long"=> 10,
                        "admin_level1"=> "test",
                        "admin_level2"=> "test",
                        "country"=> "Spain"
                    ],
                    "lang" => "es_ES"
                ],
                "vertical"=> [
                    "domain"=> "test.com",
                    "showrooms"=> []
                ],
                "information_bag"=> [
                    "parameters"=> []
                ],
                "id"=> 1
            ],
            "information_bag"=> [
                "parameters"=> ["observations"=>"This is great"]
            ],
            "created_at"=> "2014-02-12T11:19:29+0000",
            "email"=> [
                "email"=> "valid@email.com"
            ],
            "id"=> "1"
        ];

        $this->client->enableProfiler(); // Enable profiler to get the emails

        $this->client->request(
            'POST',
            '/hooks/lead/manager?apikey=1234',
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
        $this->assertEquals('Nueva solicitud de información - test.com', $message->getSubject());
        $this->assertEquals('no-reply@test.com', key($message->getFrom()));
        $this->assertEquals('validNotification@email.com', key($message->getTo()));
        $this->assertEquals('support@test.com', key($message->getCc()));
        $this->assertContains('+34 0123456789', $message->getBody());
        $this->assertContains('BIRTHDAY', $message->getBody());
        $this->assertContains('This is great', $message->getBody());
        $this->assertContains('Fecha del evento', $message->getBody());
    }

    public function testReceiveHookMailFree()
    {
        $params = [
            'event' => [
                'date' => '2015-12-31T00:00:00+0000',
                'type' => ['type' => 1, 'name' => 'BIRTHDAY'],
                'location' => [
                    "lat"=> 10,
                    "long" => 10,
                    "admin_level1" => "Getafe",
                    "admin_level2" => "Madrid",
                    "country" => "Spain"
                ]
            ],
            'personal_info' => [
                'name' => 'testUserName',
                'surnames' => 'testUserSurname',
                'email' => 'valid@email.com',
                'phone' => '+34 0123456789'
            ],
            'showroom' => [
                "slug"=> "name",
                "score"=> 0,
                "type"=>[
                    "type"=>1
                ],
                "name" => 'name',
                "phone" => '1234-call-me',
                "provider"=> [
                    "id"=> "1",
                    "name"=> "name1",
                    "slug"=> "name1",
                    "notification_emails"=> ["validNotification@email.com"],
                    "managers"=> [],
                    "location"=> [
                        "lat"=> 10,
                        "long"=> 10,
                        "admin_level1"=> "test",
                        "admin_level2"=> "test",
                        "country"=> "Spain"
                    ],
                    "lang" => "es_ES"
                ],
                "vertical"=> [
                    "domain"=> "test.com",
                    "showrooms"=> []
                ],
                "information_bag"=> [
                    "parameters"=> []
                ],
                "id"=> 1
            ],
            "information_bag"=> [
                "parameters"=> ["observations"=>"This is great"]
            ],
            "created_at"=> "2014-02-12T11:19:29+0000",
            "email"=> [
                "email"=> "valid@email.com"
            ],
            "id"=> "1"
        ];

        $this->client->enableProfiler(); // Enable profiler to get the emails

        $this->client->request(
            'POST',
            '/hooks/lead/manager?apikey=1234',
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
        $this->assertEquals('Nueva solicitud de información - test.com', $message->getSubject());
        $this->assertEquals('no-reply@test.com', key($message->getFrom()));
        $this->assertEquals('validNotification@email.com', key($message->getTo()));
        $this->assertEquals('support@test.com', key($message->getCc()));
        $this->assertContains('testUserName', $message->getBody());
        $this->assertContains('valid@email.com', $message->getBody());
        $this->assertNotContains('+34 0123456789', $message->getBody());
        $this->assertNotContains('BIRTHDAY', $message->getBody());
        $this->assertNotContains('This is great', $message->getBody());
        $this->assertContains('http://localhost/products', $message->getBody());
        $this->assertContains('Fecha del evento', $message->getBody());
    }
}
