<?php

namespace EVT\IntranetBundle\Test\Security\Voter;

use EVT\IntranetBundle\Security\Authorization\Voter\UrlVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Class UrlVoterTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class UrlVoterTest extends \PHPUnit_Framework_TestCase
{
    public function attributeProvider()
    {
        return [
            ['noAttr', false],
            ['view', true],
            ['create', true],
            ['edit', true],
            ['delete', true]
        ];
    }

    /**
     * @dataProvider attributeProvider
     */
    public function testSupportsAttribute($attribute, $response)
    {
        $predisClient = $this->getPredisMock();
        $voter = new UrlVoter($predisClient);

        $this->assertEquals($response, $voter->supportsAttribute($attribute));
    }

    public function classProvider()
    {
        return [
            ['noClass', false],
            ['string', true]
        ];
    }

    /**
     * @dataProvider classProvider
     */
    public function testSupportsClass($class, $response)
    {
        $predisClient = $this->getPredisMock();
        $voter = new UrlVoter($predisClient);

        $this->assertEquals($response, $voter->supportsClass($class));
    }

    public function testVoteNoUrl()
    {
        $predisClient = $this->getPredisMock();
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $voter = new UrlVoter($predisClient);

        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $voter->vote($token, null, []));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testArgumentsException()
    {
        $predisClient = $this->getPredisMock();
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $voter = new UrlVoter($predisClient);

        $voter->vote($token, 'route', []);
    }

    public function testVoteAttributeNotSupported()
    {
        $predisClient = $this->getPredisMock();
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $voter = new UrlVoter($predisClient);

        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $voter->vote($token, 'route', ['notSupp']));
    }

    public function testVoteNoUser()
    {
        $predisClient = $this->getPredisMock();
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');

        $token->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue(null));
        $voter = new UrlVoter($predisClient);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $voter->vote($token, 'route', ['view']));
    }

    public function redisResponseProvider()
    {
        return [
            ['0'],
            ['3'],
            [null],
            [true],
            [false]
        ];
    }

    /**
     * @dataProvider redisResponseProvider
     */
    public function testVoteNoAuthEntry($redisResponse)
    {
        $predisClient = $this->getMockBuilder('Predis\Client')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $predisClient->expects($this->atLeastOnce())
            ->method('get')
            ->will($this->returnValue($redisResponse));

        $userInterface = $this->getMockBuilder('Symfony\Component\Security\Core\User\UserInterface')
            ->setMethods(['getUsername', 'getRoles', 'getPassword', 'getSalt', 'eraseCredentials'])
            ->getMock();

        $userInterface->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('userName'));

        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');

        $token->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($userInterface));
        $voter = new UrlVoter($predisClient);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $voter->vote($token, 'route', ['view']));
    }

    public function testVoteOk()
    {
        $predisClient = $this->getMockBuilder('Predis\Client')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $predisClient->expects($this->atLeastOnce())
            ->method('get')
            ->will($this->returnValue('1'));

        $userInterface = $this->getMockBuilder('Symfony\Component\Security\Core\User\UserInterface')
            ->setMethods(['getUsername', 'getRoles', 'getPassword', 'getSalt', 'eraseCredentials'])
            ->getMock();

        $userInterface->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('userName'));

        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');

        $token->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($userInterface));
        $voter = new UrlVoter($predisClient);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $voter->vote($token, 'route', ['view']));
    }

    private function getPredisMock()
    {
        return $this->getMockBuilder('Predis\Client')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
