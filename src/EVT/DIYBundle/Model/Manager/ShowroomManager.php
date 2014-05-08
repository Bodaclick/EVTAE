<?php

namespace EVT\DIYBundle\Model\Manager;

use EVT\DIYBundle\Entity\Showroom;
use EVT\CoreClientBundle\Client\Client;
use EVT\DIYBundle\Model\Mapper\ShowroomMapper;
use EVT\EMDClientBundle\Client\ShowroomClient;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

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
     * @param Client         $coreClient        The Client
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
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
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

    /**
     * @param $id
     * @param $name
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @throws \Exception
     */
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

    /**
     * @param $id
     * @param $description
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @throws \Exception
     */
    public function changeDescription($id, $description)
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

        $showroom->setDescription($description);
        $showroom->setState(Showroom::MODIFIED);

        $this->save($showroom);
    }

    /**
     * @param $id
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function toreview($id)
    {
        if (!$this->canEdit($id)) {
            throw new AccessDeniedHttpException();
        }

        $this->changeState($id, Showroom::TOREVIEW);
    }

    public function publish($id)
    {
        if (!$this->canEdit($id)) {
            throw new AccessDeniedHttpException();
        }

        $showroom = $this->em->getRepository('EVTDIYBundle:Showroom')->findOneByEvtId($id);

        if (Showroom::TOREVIEW != $showroom->getState()) {
            throw new PreconditionFailedHttpException('Actual showroom state is: ' .$showroom->getState());
        }

        $showroom->setState(Showroom::REVIEWED);
        $this->save($showroom);

        // TODO we have to update EVT core too!!
        $this->emdShowroomClient->update($this->showroomMapper->mapModeltoWS($showroom));

        // If no exception is thrown from the EMDShowroomClient
        $this->em->remove($showroom);
        $this->em->flush();
    }

    /**
     * @param $id
     * @param $state
     * @throws \Exception
     */
    private function changeState($id, $state)
    {
        $showroom = $this->get($id);
        if (empty($showroom)) {
            throw new \Exception("Showroom not found");
        }

        $showroom->setState($state);

        $this->save($showroom);
    }

    /**
     * @param $id
     * @return bool
     */
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
