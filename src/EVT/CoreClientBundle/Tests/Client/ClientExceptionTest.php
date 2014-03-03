<?php

namespace EVT\CoreClientBundle\Tests\Client;

use Guzzle\Http\Exception\ClientErrorResponseException;
use EVT\CoreClientBundle\Client\Client;
use EVT\CoreClientBundle\Client\Response;

/**
 * ClientExceptionTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class ClientExceptionTest extends \PHPUnit_Framework_TestCase
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

        $this->request->expects($this->once())
            ->method('send')
            ->will($this->throwException(new ClientErrorResponseException('not found', 404, null)));

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with($this->equalTo('http://api.e-verticals.com/api/users/pepe'))
            ->will($this->returnValue($this->request));

        $this->clientSecurityMock = $this->getMockBuilder('EVT\CoreClientBundle\Security\ClientSecurity')
            ->disableOriginalConstructor()
            ->getMock();

        $this->clientSecurityMock->expects($this->once())
            ->method('securizeUrl')
            ->will($this->returnValue('/api/users/pepe'));

        $this->clientSecurityMock->expects($this->never())
            ->method('securizeResponse');
    }

    public function testGet()
    {
        $client = new Client($this->clientMock, 'http://api.e-verticals.com', $this->clientSecurityMock);
        $response = $client->get('/api/users/pepe');
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals([], $response->getBody());

    }
}
