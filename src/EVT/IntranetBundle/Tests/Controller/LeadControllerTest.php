<?php

namespace EVT\IntranetBundle\Test\Controller;

use EVT\CoreClientBundle\Client\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

 /**
 * LeadControllerTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class LeadControllerTest extends WebTestCase
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
        $this->client = static::createClient();
    }

    /**
     * @dataProvider getDataNotRead
     */
    public function testLead($responseJson)
    {
        $this->logIn();

        $response = new Response(200, json_decode($responseJson, true));

        $coreClientMock = $this->getMockBuilder('EVT\CoreClientBundle\Client\Client')
            ->disableOriginalConstructor()->getMock();

        $coreClientMock->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $coreClientMock->expects($this->once())
            ->method('patch')
            ->will($this->returnValue($response));

        $this->client->getContainer()->set('evt.core.client', $coreClientMock);
        $this->client->request('GET', '/manager/leads/1');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider getDataAlreadyRead
     */
    public function testLeadAlreadyReads($responseJson)
    {
        $this->logIn();

        $response = new Response(200, json_decode($responseJson, true));

        $coreClientMock = $this->getMockBuilder('EVT\CoreClientBundle\Client\Client')
            ->disableOriginalConstructor()->getMock();

        $coreClientMock->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $coreClientMock->expects($this->never())
            ->method('patch')
            ->will($this->returnValue($response));

        $this->client->getContainer()->set('evt.core.client', $coreClientMock);
        $this->client->request('GET', '/manager/leads/1');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider getDataNotRead
     */
    public function testLeadEmployeeNotRead($responseJson)
    {
        $this->logInEmployee();

        $response = new Response(200, json_decode($responseJson, true));

        $coreClientMock = $this->getMockBuilder('EVT\CoreClientBundle\Client\Client')
            ->disableOriginalConstructor()->getMock();

        $coreClientMock->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $coreClientMock->expects($this->never())
            ->method('patch')
            ->will($this->returnValue($response));

        $this->client->getContainer()->set('evt.core.client', $coreClientMock);
        $this->client->request('GET', '/employee/leads/1');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testLeads()
    {
        $this->logInEmployee();

        $response = new Response(200, json_decode(
            '{"pagination": {"total_pages": 1, "current_page": 1, "items_per_page": 10, "total_items": 7}}',
            true
        ));

        $responseVertical = new Response(200, json_decode(
            '{{"domain": "aniversarioclick.com","lang": "es_ES","timezone": "Europe/Madrid"}}',
            true
        ));

        $coreClientMock = $this->getMockBuilder('EVT\CoreClientBundle\Client\Client')
            ->disableOriginalConstructor()->getMock();

        $coreClientMock->expects($this->at(0))
            ->method('get')
            ->will($this->returnValue($response));

        $coreClientMock->expects($this->at(1))
            ->method('get')
            ->will($this->returnValue($responseVertical));

        $this->client->getContainer()->set('evt.core.client', $coreClientMock);
        $this->client->request('GET', '/employee/leads');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function getDataAlreadyRead()
    {
        return [
            [
                'data' => '
                    {
                    "event": {
                        "date": "October 15, 2014 00:00",
                        "type": {
                            "type": 1,
                            "name": "BIRTHDAY"
                        },
                        "location": {
                            "lat": 10,
                            "long": 10,
                            "admin_level1": "Parla",
                            "admin_level2": "Madrid",
                            "country": "Spain"
                        }
                    },
                    "personal_info": {
                        "name": "name",
                        "surnames": "surname",
                        "phone": "0132456789"
                    },
                    "showroom": {
                        "slug": "nombre",
                        "score": 1,
                        "type": 2,
                        "provider": {
                            "id": "1",
                            "name": "nombre",
                            "slug": "nombre",
                            "phone": "0123546 as"
                        },
                        "vertical": {
                            "domain": "test.com"
                        },
                        "id": 1
                    },
                    "information_bag": {
                        "parameters": {
                            "observations": ""
                        }
                    },
                    "created_at": "February 27, 2014 13:07",
                    "read_at": "February 27, 2014 13:07",
                    "email": {
                        "email": "email@email.com"
                    },
                    "id": "1"
                }'
            ]
        ];
    }

    public function getDataNotRead()
    {
        return [
            [
                'data' => '
                    {
                    "event": {
                        "date": "October 15, 2014 00:00",
                        "type": {
                            "type": 1,
                            "name": "BIRTHDAY"
                        },
                        "location": {
                            "lat": 10,
                            "long": 10,
                            "admin_level1": "Parla",
                            "admin_level2": "Madrid",
                            "country": "Spain"
                        }
                    },
                    "personal_info": {
                        "name": "name",
                        "surnames": "surname",
                        "phone": "0132456789"
                    },
                    "showroom": {
                        "slug": "nombre",
                        "score": 1,
                        "type": 2,
                        "provider": {
                            "id": "1",
                            "name": "nombre",
                            "slug": "nombre",
                            "phone": "0123546 as"
                        },
                        "vertical": {
                            "domain": "test.com"
                        },
                        "id": 1
                    },
                    "information_bag": {
                        "parameters": {
                            "observations": ""
                        }
                    },
                    "created_at": "February 27, 2014 13:07",
                    "email": {
                        "email": "email@email.com"
                    },
                    "id": "1"
                }'
            ]
        ];
    }
}
