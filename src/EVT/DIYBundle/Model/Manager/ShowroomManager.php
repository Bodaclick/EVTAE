<?php

namespace EVT\DIYBundle\Model\Manager;

use EVT\DIYBundle\Model\Showroom;

/**
 * Class ShowroomManager
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class ShowroomManager
{
    public function getShowroom($id)
    {
        return new Showroom($id, 'Name', 'Desc');
    }
}
