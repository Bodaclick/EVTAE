<?php

namespace EVT\IntranetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Auth
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="auth",
 *     indexes={
 *         @ORM\Index(name="route_idx", columns={"route"}),
 *         @ORM\Index(name="role_idx", columns={"role"}),
 *         @ORM\Index(name="username_idx", columns={"username"}),
 *     }
 * )
 *
 * @ORM\Entity(repositoryClass="EVT\IntranetBundle\Entity\Repository\AuthRepository")
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class Auth
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\Column(name="route", type="string", nullable=false)
     * @var $route
     */
    protected $route;

    /**
     * @ORM\Column(name="role", type="string", nullable=true)
     * @var $role
     */
    protected $role;

    /**
     * @ORM\Column(name="username", type="string", nullable=true)
     * @var $username
     */
    protected $username;

    /**
     * @ORM\Column(name="can_view", type="boolean", nullable=false)
     * @var $view
     */
    protected $view;

    /**
     * @ORM\Column(name="can_create", type="boolean", nullable=false)
     * @var $create
     */
    protected $create;

    /**
     * @ORM\Column(name="can_update", type="boolean", nullable=false)
     * @var $update
     */
    protected $update;

    /**
     * @ORM\Column(name="can_delete", type="boolean", nullable=false)
     * @var $delete
     */
    protected $delete;

    public function __construct(
        $route,
        $role = null,
        $username = null,
        $view = false,
        $create = false,
        $update = false,
        $delete = false
    ) {
        $this->route = $route;
        $this->role = $role;
        $this->username = $username;
        $this->view = $view;
        $this->create = $create;
        $this->update = $update;
        $this->delete = $delete;
    }

    /**
     * @return boolean
     */
    public function hasCreate()
    {
        return $this->create;
    }

    /**
     * @return boolean
     */
    public function hasDelete()
    {
        return $this->delete;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return boolean
     */
    public function hasUpdate()
    {
        return $this->update;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return boolean
     */
    public function hasView()
    {
        return $this->view;
    }
}
