<?php

namespace EVT\IntranetBundle\Tests\Functional\Model\Manager;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class AuthBlenderTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class AuthBlenderTest extends WebTestCase
{
    protected $client;

    /**
     * Create a client to test request and mock services
     */
    public function setUp()
    {
        $predisClient = $this->getMockBuilder('Predis\Client')
            ->disableOriginalConstructor()
            ->setMethods(['keys', 'set'])
            ->getMock();

        $predisClient->expects($this->once())
            ->method('keys')
            ->will($this->returnValue([]));

        $predisClient->expects($this->atLeastOnce())
            ->method('set')
            ->will($this->returnValue('1'));

        $this->client = static::createClient();
        $this->client->getContainer()->set('snc_redis.auth', $predisClient);
        $this->loadFixtures(
            ['EVT\IntranetBundle\Tests\DataFixtures\ORM\AuthData']
        );
    }

    public function testBlendForUser()
    {
        $authBlender = $this->client->getContainer()->get('evt_auth_manager');
        $authBlender->blendForUser('testUser', 'ROLE_EMPLOYEE');
    }
}
