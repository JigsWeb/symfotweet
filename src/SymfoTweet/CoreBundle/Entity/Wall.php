<?php

namespace SymfoTweet\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Wall
 *
 * @ORM\Table(name="wall")
 * @ORM\Entity(repositoryClass="SymfoTweet\CoreBundle\Repository\WallRepository")
 */
class Wall
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="walls")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active_admin", type="boolean", nullable=false)
     */
    private $active_admin;

    /**
     * @ORM\OneToOne(targetEntity="WallParams",cascade={"persist","remove"})
     * @ORM\JoinColumn(name="params_id", referencedColumnName="id")
     */
    private $params;

    public function __construct() {
        $this->user = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser(){
        return $this->user;
    }

    public function setUser($user){
        $this->user = $user;

        return $this->user;
    }

    /**
     * Get active_admin
     *
     * @return boolean
     */
    public function getActiveAdmin(){
        return $this->active_admin;
    }

    public function setActiveAdmin($active_admin){
        $this->active_admin = $active_admin;

        return $this;
    }

    public function getParams(){
        return $this->params;
    }

    public function setParams($params){
        $this->params = $params;

        return $this;
    }
}
