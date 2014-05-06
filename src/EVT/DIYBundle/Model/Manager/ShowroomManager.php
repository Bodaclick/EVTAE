<?php

namespace EVT\DIYBundle\Model\Manager;

use EVT\DIYBundle\Model\Mapper\ShowroomMapper;
use EVT\EMDClientBundle\Client\ShowroomClient;

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

    public function __construct(ShowroomClient $emdShowroomClient, ShowroomMapper $showroomMapper, $em)
    {
        $this->emdShowroomClient = $emdShowroomClient;
        $this->showroomMapper = $showroomMapper;
        $this->em = $em;
    }

    public function get($id)
    {
        //Chech if already in house
        $dbShowroom = $this->em->getRepository('EVTDIYBundle:Showroom')->findOneByEvtId($id);

        if (null !== $dbShowroom) {
            return $this->showroomMapper->mapDBtoModel($dbShowroom);
        }

        $emdShowroom = $this->emdShowroomClient->getById($id);

        return $this->showroomMapper->mapWStoModel($emdShowroom);
    }
}
