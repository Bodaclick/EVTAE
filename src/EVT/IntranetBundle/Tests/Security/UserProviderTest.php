<?php

namespace EVT\IntranetBundle\Test\Security;

use EVT\CoreClientBundle\Client\Response;
use EVT\IntranetBundle\Security\EVTUser;
use EVT\IntranetBundle\Security\UserProvider;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * UserProviderTest
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class UserProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportClass()
    {
        $client = $this->getMockBuilder('EVT\CoreClientBundle\Client\Client')->disableOriginalConstructor()->getMock();
        $session = new Session(new MockArraySessionStorage());

        $customProvider = new UserProvider($client, $session);
        $this->assertTrue($customProvider->supportsClass('EVT\IntranetBundle\Security\EVTUser'));
    }

    public function testLoadUserByUsername()
    {
        $response = new Response('200', json_decode(
            '{
            "id":1,
            "username":"usernameManager",
            "email":{"email":"valid@emailManager.com"},
            "salt":"sywh",
            "password":"KKOhKzmyHK",
            "personal_info":{"name":"nameManager","surnames":"surnamesManager","phone":"0132465987"},
            "roles":["ROLE_MANAGER","ROLE_USER"]}',
            true
        )) ;

        $client = $this->getMockBuilder('EVT\CoreClientBundle\Client\Client')->disableOriginalConstructor()->getMock();
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
            ->disableOriginalConstructor()->getMock();

        $session->expects($this->once())
            ->method('set')
            ->with($this->equalTo('_role'), $this->equalTo('manager'));

        $client->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/api/users/usernameManager'))
            ->will($this->returnValue($response));

        $customProvider = new UserProvider($client, $session);
        $user = $customProvider->loadUserByUsername('usernameManager');
        $this->assertInstanceOf('EVT\IntranetBundle\Security\EVTUser', $user);
        $this->assertEquals('usernameManager', $user->getUsername());
        $this->assertEquals('KKOhKzmyHK', $user->getPassword());
        $this->assertEquals('sywh', $user->getSalt());
        $this->assertContains('ROLE_MANAGER', $user->getRoles());
    }

    public function testRefreshUser()
    {
        $response = new Response('200', json_decode(
            '{
            "id":1,
            "username":"usernameManager",
            "email":{"email":"valid@emailManager.com"},
            "salt":"sywh",
            "password":"KKOhKzmyHK",
            "personal_info":{"name":"nameManager","surnames":"surnamesManager","phone":"0132465987"},
            "roles":["ROLE_MANAGER","ROLE_USER"]}',
            true
        )) ;

        $client = $this->getMockBuilder('EVT\CoreClientBundle\Client\Client')->disableOriginalConstructor()->getMock();
        $session = new Session(new MockArraySessionStorage());

        $client->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/api/users/usernameManager'))
            ->will($this->returnValue($response));

        $customProvider = new UserProvider($client, $session);
        $user = $customProvider->refreshUser(
            new EVTUser('usernameManager', 'KKOhKzmyHK', 'sywh', ["ROLE_MANAGER", "ROLE_USER"])
        );

        $this->assertInstanceOf('EVT\IntranetBundle\Security\EVTUser', $user);
        $this->assertEquals('usernameManager', $user->getUsername());
    }
}
