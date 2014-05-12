<?php

namespace EVT\DIYBundle\Tests\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EVT\DIYBundle\Entity\Showroom;

/**
 * LoadShowroomData
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class LoadShowroomData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $showroom = new Showroom(1, 'name_i', 'desc_i');

        $manager->persist($showroom);

        $manager->flush();
    }
}
