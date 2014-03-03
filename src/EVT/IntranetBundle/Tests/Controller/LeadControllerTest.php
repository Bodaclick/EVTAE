<?php

namespace EVT\IntranetBundle\Test\Controller;

use EVT\CoreClientBundle\Client\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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

    /**
     * Create a client to test request and mock services
     */
    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testLeads()
    {
        $this->logIn();

        $responseJson = '
        {
            "items": [
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
            }
        ],
        "pagination": {
            "total_pages": 1,
            "current_page": 1,
            "items_per_page":1,
            "total_items": 1
        }
        }';

        $response = new Response(200, json_decode($responseJson, true));

        $coreClientMock = $this->getMockBuilder('EVT\CoreClientBundle\Client\Client')
            ->disableOriginalConstructor()->getMock();

        $coreClientMock->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $this->client->getContainer()->set('evt.core.client', $coreClientMock);
        $this->client->request('GET', '/leads');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }


    public function testLead()
    {
        $this->logIn();

        $responseJson = '
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
        }';

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
        $this->client->request('GET', '/leads/1');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testLeadAlreadyReads()
    {
        $this->logIn();

        $responseJson = '
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
        }';

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
        $this->client->request('GET', '/leads/1');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'admin_secured_area';
        $token = new UsernamePasswordToken('manager', null, $firewall, array('ROLE_MANAGER'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
