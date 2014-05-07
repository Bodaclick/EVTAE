<?php

namespace EVT\DIYBundle\Model\Manager;

use EVT\DIYBundle\Model\Mapper\ShowroomMapper;
use EVT\EMDClientBundle\Client\ShowroomClient;
use Doctrine\ORM\EntityManager;

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

    /**
     *  Construct
     *
     * @param ShowroomClient $emdShowroomClient The showroom client
     * @param ShowroomMapper $showroomMapper    The Showroom mapper
     * @param EntityManager  $em                The EntityManager
     */
    public function __construct(ShowroomClient $emdShowroomClient, ShowroomMapper $showroomMapper, EntityManager $em)
    {
        $this->emdShowroomClient = $emdShowroomClient;
        $this->showroomMapper = $showroomMapper;
        $this->em = $em;
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
        //Chech if already in house
        $dbShowroom = $this->em->getRepository('EVTDIYBundle:Showroom')->findOneByEvtId($id);

        if (null !== $dbShowroom) {
            return $dbShowroom;
        }

        $emdShowroom = $this->emdShowroomClient->getById($id);

        return $this->showroomMapper->mapWStoModel($emdShowroom);
    }
}
