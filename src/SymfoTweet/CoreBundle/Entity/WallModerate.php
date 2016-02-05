<?php

namespace SymfoTweet\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WallModerate
 *
 * @ORM\Table(name="wall_moderate")
 * @ORM\Entity(repositoryClass="SymfoTweet\CoreBundle\Repository\WallModerateRepository")
 */
class WallModerate
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
     * @ORM\ManyToOne(targetEntity="Wall", inversedBy="tweets")
     * @ORM\JoinColumn(name="wall_id", referencedColumnName="id")
     */
    private $wall;

    /**
     * @var string
     *
     * @ORM\Column(name="id_str", type="string")
     */
    private $id_str;

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
     * Set id_str
     *
     * @param string $idStr
     * @return WallModerate
     */
    public function setIdStr($idStr)
    {
        $this->id_str = $idStr;

        return $this;
    }

    /**
     * Get id_str
     *
     * @return string
     */
    public function getIdStr()
    {
        return $this->id_str;
    }

    /**
     * Set wall
     *
     * @param \SymfoTweet\CoreBundle\Entity\Wall $wall
     * @return WallModerate
     */
    public function setWall(\SymfoTweet\CoreBundle\Entity\Wall $wall = null)
    {
        $this->wall = $wall;

        return $this;
    }

    /**
     * Get wall
     *
     * @return \SymfoTweet\CoreBundle\Entity\Wall
     */
    public function getWall()
    {
        return $this->wall;
    }
}
