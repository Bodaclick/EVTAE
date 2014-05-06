<?php

namespace EVT\DIYBundle\Model\Mapper;

use EVT\DIYBundle\Entity\Showroom;

/**
 * Class ShowroomMapper
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class ShowroomMapper
{
    /**
     * @param $wsModel
     *
     * @return Showroom
     */
    public function mapWStoModel($wsModel)
    {
        return new Showroom(
            $wsModel['id'],
            $wsModel['name'],
            $wsModel['description']
        );
    }
}
