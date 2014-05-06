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

    public function __construct(ShowroomClient $emdShowroomClient, ShowroomMapper $showroomMapper)
    {
        $this->emdShowroomClient = $emdShowroomClient;
        $this->showroomMapper = $showroomMapper;
    }

    public function get($id)
    {
        $emdShowroom = $this->emdShowroomClient->getById($id);

        return $this->showroomMapper->mapWStoModel($emdShowroom);
    }
}
