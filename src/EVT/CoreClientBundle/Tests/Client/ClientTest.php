<?php

namespace EVT\CoreClientBundle\Tests\Client;

use EVT\CoreClientBundle\Client\Client;
use EVT\CoreClientBundle\Client\Response as EVTResponse;

/**
 * ClientTest
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var mock Guzzle\Http\ClientInterface
     */
    protected $clientMock;

    /**
     * @var Request $request
     */
    protected $request;

    /**
     * @var Response $response
     */
    protected $response;

    protected $clientSecurityMock;

    /**
     * SetUp
     */
    public function setUp()
    {
        $this->clientMock = $this->getMockForAbstractClass('Guzzle\Http\ClientInterface');

        $this->request = $this->getMockBuilder('Guzzle\Http\Message\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->response = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $this->request->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->response));

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with($this->equalTo('http://api.e-verticals.com/api/users/pepe?apikey=1234'))
            ->will($this->returnValue($this->request));

        $this->clientSecurityMock = $this->getMockBuilder('EVT\CoreClientBundle\Security\ClientSecurity')
            ->disableOriginalConstructor()
            ->getMock();

        $this->clientSecurityMock->expects($this->once())
            ->method('securizeUrl')
            ->will($this->returnValue('/api/users/pepe'));

        $this->clientSecurityMock->expects($this->once())
            ->method('securizeResponse')
            ->will($this->returnValue(new EVTResponse('200',['nombre' => 'pepe'])));

    }

    public function testGet()
    {
        $client = new Client($this->clientMock, '1234', 'http://api.e-verticals.com', $this->clientSecurityMock);
        $response = $client->sendRequest('/api/users/pepe');
        $this->assertEquals($response->getBody(), ['nombre' => 'pepe']);

    }
}
