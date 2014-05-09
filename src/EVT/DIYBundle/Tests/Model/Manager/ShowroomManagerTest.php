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
    private $showroomClientMockup;
    private $showroomMapperMockup;
    private $showroomRepoMockup;
    private $emMockup;
    private $coreClientMockup;
    private $manager;

    public function testNewShowroom()
    {
        $this->setEmdShowroomMock();
        $mappedShowroom = new Showroom(1, 'Name', 'Desc');
        $this->setShowroomMapperMockup($mappedShowroom);
        $this->setShowroomRepoMockup(null);
        $this->setEntityManagerMockup();
        $this->setCoreClientMockup();

        $this->buildManager();
        $showroom = $this->manager->get(1);

        $this->assertEquals(1, $showroom->getEvtId());
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function testNewShowroomCannot()
    {
        $this->setEmptyEmdShowroomMock();
        $mappedShowroom = new Showroom(1, 'Name', 'Desc');
        $this->setEmptyShowroomMapperMockup($mappedShowroom);
        $this->setEmptyShowroomRepoMockup(null);
        $this->setEmptyEntityManagerMockup();
        $this->setCoreClientMockup(404);

        $this->buildManager();
        $showroom = $this->manager->get(1);

        $this->assertEquals(1, $showroom->getEvtId());
    }

    public function testShowroomAlreadyModified()
    {
        $this->setEmptyEmdShowroomMock();
        $this->setEmptyShowroomMapperMockup();

        $dbShowroom = new Showroom(1, 'Name', 'Desc');
        $this->setShowroomRepoMockup($dbShowroom);
        $this->setEntityManagerMockup();
        $this->setCoreClientMockup();

        $this->buildManager();
        $showroom = $this->manager->get(1);

        $this->assertEquals(1, $showroom->getEvtId());
    }

    public function testShowroomNotFound()
    {
        $this->setEmdShowroomMock();
        $this->setShowroomMapperMockup(null);
        $this->setShowroomRepoMockup(null);
        $this->setEntityManagerMockup();
        $this->setCoreClientMockup();

        $this->buildManager();
        $showroom = $this->manager->get(1);

        $this->assertNull($showroom);
    }

    public function testActiveEditionShowroomExist()
    {
        $this->setEmptyEmdShowroomMock();
        $this->setEmptyShowroomMapperMockup();

        $dbShowroom = new Showroom(1, 'Name', 'Desc');
        $dbShowroom->setState(Showroom::MODIFIED);
        $this->setShowroomRepoMockup($dbShowroom);
        $this->setEntityManagerMockup();
        $this->setCoreClientMockup();

        $this->buildManager();
        $this->manager->activeEdition(1);

        $this->assertNotEquals(Showroom::REVIEWED, $dbShowroom->getState());
        $this->assertNotEquals(Showroom::TOREVIEW, $dbShowroom->getState());
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException
     */
    public function testActiveNotEditionShowroomExist()
    {
        $this->setEmptyEmdShowroomMock();
        $this->setEmptyShowroomMapperMockup();

        $dbShowroom = new Showroom(1, 'Name', 'Desc');
        $dbShowroom->setState(Showroom::REVIEWED);
        $this->setShowroomRepoMockup($dbShowroom);
        $this->setEntityManagerMockup();
        $this->setCoreClientMockup();

        $this->buildManager();
        $this->manager->activeEdition(1);
    }

    public function testActiveEditionShowroomEMDExist()
    {
        $this->setEmdShowroomMock();
        $mappedShowroom = new Showroom(1, 'Name', 'Desc');
        $this->setShowroomMapperMockup($mappedShowroom);
        $this->setShowroomRepoMockup(null);
        $this->setEntityManagerMockup();
        $this->setCoreClientMockup();

        $this->buildManager();
        $this->manager->activeEdition(1);

        $this->assertNotEquals(Showroom::REVIEWED, $mappedShowroom->getState());
        $this->assertNotEquals(Showroom::TOREVIEW, $mappedShowroom->getState());
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testActiveEditionShowroomNotExist()
    {
        $this->markTestIncomplete('This test depends everymundo.');

        $this->setEmdShowroomMock();
        $this->setShowroomMapperMockup(null);
        $this->setShowroomRepoMockup(null);
        $this->setEntityManagerMockup();
        $this->setCoreClientMockup();

        $this->buildManager();
        $this->manager->activeEdition(null);
    }

    private function setEmdShowroomMock()
    {
        $this->showroomClientMockup = $this->getMockBuilder('EVT\EMDClientBundle\Client\ShowroomClient')
            ->disableOriginalConstructor()->getMock();
        $this->showroomClientMockup->expects($this->once())
            ->method('getById')
            ->will($this->returnvalue(1));
    }

    private function setEmptyEmdShowroomMock()
    {
        $this->showroomClientMockup = $this->getMockBuilder('EVT\EMDClientBundle\Client\ShowroomClient')
            ->disableOriginalConstructor()->getMock();
        $this->showroomClientMockup->expects($this->never())
            ->method('getById');
    }

    private function setShowroomMapperMockup($showroom)
    {
        $this->showroomMapperMockup = $this->getMockBuilder('EVT\DIYBundle\Model\Mapper\ShowroomMapper')
            ->disableOriginalConstructor()->getMock();
        $this->showroomMapperMockup->expects($this->once())
            ->method('mapWStoModel')
            ->will($this->returnvalue($showroom));
    }

    private function setEmptyShowroomMapperMockup()
    {
        $this->showroomMapperMockup = $this->getMockBuilder('EVT\DIYBundle\Model\Mapper\ShowroomMapper')
            ->disableOriginalConstructor()->getMock();
        $this->showroomMapperMockup->expects($this->never())
            ->method('mapWStoModel');
    }

    private function setShowroomRepoMockup($showroom)
    {
        $this->showroomRepoMockup = $this->getMockBuilder('Doctrine\ORM\EntityRepository')->disableOriginalConstructor()
            ->setMethods(['findOneByEvtId'])
            ->getMock();
        $this->showroomRepoMockup->expects($this->once())
            ->method('findOneByEvtId')
            ->will($this->returnvalue($showroom));
    }

    private function setEmptyShowroomRepoMockup($showroom)
    {
        $this->showroomRepoMockup = $this->getMockBuilder('Doctrine\ORM\EntityRepository')->disableOriginalConstructor()
            ->setMethods(['findOneByEvtId'])
            ->getMock();
        $this->showroomRepoMockup->expects($this->never())
            ->method('findOneByEvtId');
    }

    private function setEntityManagerMockup()
    {
        $this->emMockup = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->emMockup->expects($this->once())
            ->method('getRepository')
            ->will($this->returnvalue($this->showroomRepoMockup));
    }

    private function setEmptyEntityManagerMockup()
    {
        $this->emMockup = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->emMockup->expects($this->never())
            ->method('getRepository');
    }

    private function setCoreClientMockup($responseCode = 200)
    {
        $this->coreClientMockup = $this->getMockBuilder('EVT\CoreClientBundle\Client\Client')
            ->disableOriginalConstructor()->getMock();
        $this->coreClientMockup->expects($this->once())
            ->method('get')
            ->will($this->returnvalue(new Response($responseCode, '')));
    }

    private function buildManager()
    {
        $this->manager = new ShowroomManager(
            $this->showroomClientMockup,
            $this->showroomMapperMockup,
            $this->emMockup,
            $this->coreClientMockup
        );
    }
}
