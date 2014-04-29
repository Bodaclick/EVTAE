<?php

namespace EVT\IntranetBundle\Tests\Functional\Repository;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class AuthRepositoryTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class AuthRepositoryTest extends WebTestCase
{
    private $repo;

    public function setUp()
    {
        $classes = [
            'EVT\IntranetBundle\Tests\DataFixtures\ORM\AuthData',
        ];
        $this->loadFixtures($classes);
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->repo = static::$kernel->getContainer()->get('doctrine')->getManager()
            ->getRepository('EVTIntranetBundle:Auth');
    }

    public function testFindByUserNameOrRole()
    {
        $auths = $this->repo->findByUserNameOrRole('testName', 'ROLE_EMPLOYEE');

        $this->assertCount(3, $auths);
        $this->assertEquals('ROLE_EMPLOYEE', $auths[0]->getRole());
        $this->assertEquals('ROLE_EMPLOYEE', $auths[1]->getRole());
        // The sort is fundamental for the correct applicacion of the auths
        $this->assertEquals('testName', $auths[2]->getUserName());
    }
}
