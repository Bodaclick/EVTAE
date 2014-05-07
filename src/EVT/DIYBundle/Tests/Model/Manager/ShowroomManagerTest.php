<?php

namespace EVT\DIYBundle\Tests\Model\Manager;

use EVT\CoreClientBundle\Client\Response;
use EVT\DIYBundle\Entity\Showroom;
use EVT\DIYBundle\Model\Manager\ShowroomManager;

/**
 * Class ShowroomManagerTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class ShowroomManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testNewShowroom()
    {
        $showroomClient = $this->getMockBuilder('EVT\EMDClientBundle\Client\ShowroomClient')
            ->disableOriginalConstructor()->getMock();
        $showroomClient->expects($this->once())
            ->method('getById')
            ->will($this->returnvalue(1));

        $mappedShowroom = new Showroom(1, 'Name', 'Desc');
        $showroomMapper = $this->getMockBuilder('EVT\DIYBundle\Model\Mapper\ShowroomMapper')
            ->disableOriginalConstructor()->getMock();
        $showroomMapper->expects($this->once())
            ->method('mapWStoModel')
            ->will($this->returnvalue($mappedShowroom));

        $showroomRepo = $this->getMockBuilder('Doctrine\ORM\EntityRepository')->disableOriginalConstructor()
            ->setMethods(['findOneByEvtId'])
            ->getMock();
        $showroomRepo->expects($this->once())
            ->method('findOneByEvtId')
            ->will($this->returnvalue(null));

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $em->expects($this->once())
            ->method('getRepository')
            ->will($this->returnvalue($showroomRepo));

        $coreClient = $this->getMockBuilder('EVT\CoreClientBundle\Client\Client')
            ->disableOriginalConstructor()->getMock();
        $coreClient->expects($this->once())
            ->method('get')
            ->will($this->returnvalue(new Response(200, '')));

        $manager = new ShowroomManager($showroomClient, $showroomMapper, $em, $coreClient);
        $showroom = $manager->get(1);

        $this->assertEquals(1, $showroom->getEvtId());
    }

    public function testShowroomAlreadyModified()
    {
        $showroomClient = $this->getMockBuilder('EVT\EMDClientBundle\Client\ShowroomClient')
            ->disableOriginalConstructor()->getMock();
        $showroomClient->expects($this->never())
            ->method('getById');

        $showroomMapper = $this->getMockBuilder('EVT\DIYBundle\Model\Mapper\ShowroomMapper')
            ->disableOriginalConstructor()->getMock();
        $showroomMapper->expects($this->never())
            ->method('mapWStoModel');

        $dbShowroom = new Showroom(1, 'Name', 'Desc');
        $showroomRepo = $this->getMockBuilder('Doctrine\ORM\EntityRepository')->disableOriginalConstructor()
            ->setMethods(['findOneByEvtId'])
            ->getMock();
        $showroomRepo->expects($this->once())
            ->method('findOneByEvtId')
            ->will($this->returnvalue($dbShowroom));

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $em->expects($this->once())
            ->method('getRepository')
            ->will($this->returnvalue($showroomRepo));

        $coreClient = $this->getMockBuilder('EVT\CoreClientBundle\Client\Client')
            ->disableOriginalConstructor()->getMock();
        $coreClient->expects($this->once())
            ->method('get')
            ->will($this->returnvalue(new Response(200, '')));

        $manager = new ShowroomManager($showroomClient, $showroomMapper, $em, $coreClient);
        $showroom = $manager->get(1);

        $this->assertEquals(1, $showroom->getEvtId());
    }

    public function testShowroomNotFound()
    {
        $showroomClient = $this->getMockBuilder('EVT\EMDClientBundle\Client\ShowroomClient')
            ->disableOriginalConstructor()->getMock();
        $showroomClient->expects($this->once())
            ->method('getById')
            ->will($this->returnvalue(1));

        $showroomMapper = $this->getMockBuilder('EVT\DIYBundle\Model\Mapper\ShowroomMapper')
            ->disableOriginalConstructor()->getMock();
        $showroomMapper->expects($this->once())
            ->method('mapWStoModel')
            ->will($this->returnvalue(null));

        $showroomRepo = $this->getMockBuilder('Doctrine\ORM\EntityRepository')->disableOriginalConstructor()
            ->setMethods(['findOneByEvtId'])
            ->getMock();
        $showroomRepo->expects($this->once())
            ->method('findOneByEvtId')
            ->will($this->returnvalue(null));

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $em->expects($this->once())
            ->method('getRepository')
            ->will($this->returnvalue($showroomRepo));

        $coreClient = $this->getMockBuilder('EVT\CoreClientBundle\Client\Client')
            ->disableOriginalConstructor()->getMock();
        $coreClient->expects($this->once())
            ->method('get')
            ->will($this->returnvalue(new Response(200, '')));

        $manager = new ShowroomManager($showroomClient, $showroomMapper, $em, $coreClient);
        $showroom = $manager->get(1);

        $this->assertNull($showroom);
    }
}
