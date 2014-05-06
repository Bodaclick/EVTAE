<?php

namespace EVT\DIYBundle\Model;

/**
 * Class Showroom
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

    private $id;
    private $name;
    private $description;
    private $state;

    public function __construct($id, $name, $descr, $state = self::RETRIVED)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $descr;
        $this->state = $state;
    }

    public function changeName($name)
    {
        $this->name = $name;
    }

    public function changeDescription($desc)
    {
        $this->description = $desc;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }
}
