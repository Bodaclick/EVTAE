<?php

namespace EVT\IntranetBundle\Test\Security;

use EVT\CoreClientBundle\Client\Response;
use EVT\IntranetBundle\Security\EVTUser;
use EVT\IntranetBundle\Security\UserProvider;

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

        $customProvider = new UserProvider($client);
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

        $client->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/api/users/usernameManager'))
            ->will($this->returnValue($response));

        $customProvider = new UserProvider($client);
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

        $client->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/api/users/usernameManager'))
            ->will($this->returnValue($response));

        $customProvider = new UserProvider($client);
        $user = $customProvider->refreshUser(
            new EVTUser('usernameManager', 'KKOhKzmyHK', 'sywh', ["ROLE_MANAGER", "ROLE_USER"])
        );

        $this->assertInstanceOf('EVT\IntranetBundle\Security\EVTUser', $user);
        $this->assertEquals('usernameManager', $user->getUsername());
    }
}
