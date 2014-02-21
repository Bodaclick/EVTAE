<?php

namespace EVT\CoreClientBundle\Tests\Client;

use EVT\CoreClientBundle\Client\Client;

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

        $this->response->expects($this->once())
            ->method('json')
            ->will($this->returnValue('{"nombre":"pepe"}'));

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with($this->equalTo('http://api.e-verticals.com/users/pepe?apikey=1234'))
            ->will($this->returnValue($this->request));
    }

    public function testGet()
    {
        $client = new Client($this->clientMock, '1234', 'http://api.e-verticals.com');
        $response = $client->sendRequest('/users/pepe');
        $this->assertEquals($response, '{"nombre":"pepe"}');

    }
}
