<?php

namespace EVT\CoreClientBundle\Tests\Security;

use Guzzle\Http\Message\Response;
use EVT\CoreClientBundle\Security\ClientSecurity;

/**
 * ClientSecurityTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class ClientSecurityTest extends \PHPUnit_Framework_TestCase
{
    public function testSecurizeUrl()
    {
        $sClient = new ClientSecurity($this->getContainer(), 'apikeyValue');

        $this->assertEquals('?apikey=apikeyValue&canView=test@test.com', $sClient->securizeUrl(''));
        $this->assertEquals('/api/test?apikey=apikeyValue&canView=test@test.com', $sClient->securizeUrl('/api/test'));
    }

    public function testSecurizeResponse()
    {
        $sClient = new ClientSecurity($this->getContainer(), 'apikeyValue');

        $response = new Response(200, null, '{"data":"test"}');
        $sResponse = $sClient->securizeResponse($response);
        $this->assertEquals(200, $sResponse->getStatusCode());
        $this->assertEquals(['data'=>'test'], $sResponse->getBody());

        $response = new Response(404, null, '{}');
        $sResponse = $sClient->securizeResponse($response);
        $this->assertEquals(404, $sResponse->getStatusCode());
        $this->assertEquals([], $sResponse->getBody());

        $response = new Response(404, null, '');
        $sResponse = $sClient->securizeResponse($response);
        $this->assertEquals(404, $sResponse->getStatusCode());
        $this->assertEquals([], $sResponse->getBody());
    }

    public function testSecurizeUrlWithNoTocken()
    {
        $secContainerMock = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContextInterface')
            ->disableOriginalConstructor()->getMock();
        $secContainerMock->expects($this->any())->method('getToken')->will($this->returnValue(null));

        $containerMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')
            ->disableOriginalConstructor()->getMock();
        $containerMock->expects($this->any())->method('get')->will($this->returnValue($secContainerMock));
        $sClient = new ClientSecurity($containerMock, 'apikeyValue');

        $this->assertEquals('?apikey=apikeyValue', $sClient->securizeUrl(''));
        $this->assertEquals('/api/test?apikey=apikeyValue', $sClient->securizeUrl('/api/test'));
    }

    private function getContainer()
    {
        $secTokenMock = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')
            ->disableOriginalConstructor()->getMock();
        $secTokenMock->expects($this->any())->method('getUsername')->will($this->returnValue('test@test.com'));
        $secTokenMock->expects($this->any())->method('getRoles')
            ->will($this->returnValue(['ROLE_TEST', 'ROLE_USER']));

        $secContainerMock = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContextInterface')
            ->disableOriginalConstructor()->getMock();
        $secContainerMock->expects($this->any())->method('getToken')->will($this->returnValue($secTokenMock));

        $containerMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')
            ->disableOriginalConstructor()->getMock();
        $containerMock->expects($this->any())->method('get')->will($this->returnValue($secContainerMock));

        return $containerMock;
    }
}
