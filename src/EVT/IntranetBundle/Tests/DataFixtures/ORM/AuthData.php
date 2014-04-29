<?php

namespace EVT\IntranetBundle\Tests\DataFixtures\ORM;

use EVT\IntranetBundle\Entity\Auth;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class AuthData
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class AuthData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $auths =[
            ['route1', 'ROLE_EMPLOYEE', null, true],
            ['route2', null, 'testName', true],
            ['route2', 'ROLE_EMPLOYEE', null, true],
            ['route2', 'ROLE_MANAGER', null, true],
            ['route3', null, 'otherName', true]
        ];

        foreach ($auths as $auth) {
            $authDB = new Auth(
                $auth[0],
                $auth[1],
                $auth[2],
                $auth[3]
            );

            $manager->persist($authDB);
        }

        $manager->flush();
    }
}
