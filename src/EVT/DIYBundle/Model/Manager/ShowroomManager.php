<?php

namespace EVT\DIYBundle\Model\Manager;

use EVT\DIYBundle\Entity\Showroom;
use EVT\CoreClientBundle\Client\Client;
use EVT\DIYBundle\Model\Mapper\ShowroomMapper;
use EVT\EMDClientBundle\Client\ShowroomClient;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class ShowroomManager
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class ShowroomManager
{
    private $emdShowroomClient;
    private $showroomMapper;
    private $em;
    private $coreClient;

    /**
     *  Construct
     *
     * @param ShowroomClient $emdShowroomClient The showroom client
     * @param ShowroomMapper $showroomMapper    The Showroom mapper
     * @param EntityManager  $em                The EntityManager
     * @param Client $coreClient                The Client
     */
    public function __construct(
        ShowroomClient $emdShowroomClient,
        ShowroomMapper $showroomMapper,
        EntityManager $em,
        Client $coreClient
    ) {
        $this->emdShowroomClient = $emdShowroomClient;
        $this->showroomMapper = $showroomMapper;
        $this->em = $em;
        $this->coreClient = $coreClient;
    }

    /**
     * Return the showroom with evt_id = $id
     *
     * @param int $id The EVT_ID of the finded showroom
     *
     * @return \EVT\DIYBundle\Entity\Showroom
     */
    public function get($id)
    {
        if (!$this->canEdit($id)) {
            throw new AccessDeniedHttpException();
        }

        //Check if already in house
        $dbShowroom = $this->em->getRepository('EVTDIYBundle:Showroom')->findOneByEvtId($id);

        if (null !== $dbShowroom) {
            return $dbShowroom;
        }

        $emdShowroom = $this->emdShowroomClient->getById($id);

        return $this->showroomMapper->mapWStoModel($emdShowroom);
    }

    public function changeName($id, $name)
    {
        if (!$this->canEdit($id)) {
            throw new AccessDeniedHttpException();
        }

        $showroom = $this->em->getRepository('EVTDIYBundle:Showroom')->findOneByEvtId($id);
        if (empty($showroom)) {
            throw new \Exception("Showroom not found");
        }

        if (Showroom::REVIEWED == $showroom->getState()) {
            throw new AccessDeniedHttpException();
        }

        $showroom->setName($name);
        $showroom->setState(Showroom::MODIFIED);

        $this->save($showroom);
    }

    public function changeDescription($id, $description)
    {
        if (!$this->canEdit($id)) {
            throw new AccessDeniedHttpException();
        }

        $showroom = $this->em->getRepository('EVTDIYBundle:Showroom')->findOneByEvtId($id);;
        if (empty($showroom)) {
            throw new \Exception("Showroom not found");
        }

        if (Showroom::REVIEWED == $showroom->getState()) {
            throw new AccessDeniedHttpException();
        }

        $showroom->setDescription($description);
        $showroom->setState(Showroom::MODIFIED);

        $this->save($showroom);
    }

    public function toreview($id)
    {
        if (!$this->canEdit($id)) {
            throw new AccessDeniedHttpException();
        }

        $this->changeState($id, Showroom::TOREVIEW);
    }

    private function changeState($id, $state)
    {
        $showroom = $this->get($id);
        if (empty($showroom)) {
            throw new \Exception("Showroom not found");
        }

        $showroom->changeState($state);

        $this->save($showroom);
    }

    private function canEdit($id)
    {
        //Check if user can modify the showroom.
        $coreShowroom = $this->coreClient->get('/api/showrooms/'.$id);
        if (200 != $coreShowroom->getStatusCode()) {
            return false;
        }
        return true;
    }

    private function save($showroom)
    {
        $this->em->persist($showroom);
        $this->em->flush();
    }
}
