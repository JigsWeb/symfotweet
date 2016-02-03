<?php
namespace SymfoTweet\CoreBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use SymfoTweet\CoreBundle\Service\Twitter;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_id", type="string", nullable=true)
     */
    protected $twitter_id;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token", type="string", nullable=true)
     */
    protected $access_token;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token_secret", type="string", nullable=true)
     */
    protected $access_token_secret;

    /**
     * @ORM\OneToMany(targetEntity="Wall", mappedBy="user")
     */
    private $walls;

    public function setTwitterId($twitter_id){
      $this->twitter_id = $twitter_id;

      return $this;
    }

    public function getTwitterId(){
      return $this->twitter_id;
    }

    public function setAccessTokenSecret($access_token_secret){
      $this->access_token_secret = $access_token_secret;

      return $this;
    }

    public function getAccessTokenSecret(){
      return $this->access_token_secret;
    }

    public function setAccessToken($access_token){
      $this->access_token = $access_token;

      return $this;
    }

    public function getAccessToken(){
      return $this->access_token;
    }

    public function getWalls(){
      return $this->walls;
    }
}
