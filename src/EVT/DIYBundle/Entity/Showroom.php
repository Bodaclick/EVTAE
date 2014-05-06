<?php

namespace EVT\DIYBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Showroom
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="showroom",
 *     indexes={
 *         @ORM\Index(name="evt_id_idx", columns={"evt_id"}),
 *     }
 * )
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class Showroom
{
    const RETRIVED = 0;
    const MODIFIED = 1;
    const TOREVIEW = 2;
    const REVIEWED = 3;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\Column(name="evt_id", type="integer")
     * @var integer $evt_id
     */
    protected $evtId;

    /**
     * @ORM\Column(name="name", type="string", nullable=false)
     * @var $name
     */
    protected $name;

    /**
     * @ORM\Column(name="description", type="string", nullable=false)
     * @var $description
     */
    protected $description;

    /**
     * @ORM\Column(name="state", type="integer", nullable=false)
     * @var integer $state
     */
    protected $state;

    public function __construct($id, $name, $descr, $state = self::RETRIVED)
    {
        $this->evtId = $id;
        $this->name = $name;
        $this->description = $descr;
        $this->state = $state;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $evtId
     */
    public function setEvtId($evtId)
    {
        $this->evtId = $evtId;
    }

    /**
     * @return int
     */
    public function getEvtId()
    {
        return $this->evtId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }
}
