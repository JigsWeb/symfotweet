<?php

namespace SymfoTweet\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WallParams
 *
 * @ORM\Table(name="wall_params")
 * @ORM\Entity(repositoryClass="SymfoTweet\CoreBundle\Repository\WallParamsRepository")
 */
class WallParams
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
     * @var string
     *
     * @ORM\Column(name="type", type="string")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string")
     */
    private $text;

    public function __construct($type=false,$text=false){
      if($type){
        $this->setType($type);
      }
      if($text){
        $this->setText($text);
      }
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
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @return WallParams
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set text
     *
     * @return WallParams
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    public function getTypeChoice(){
      return array(
        "Hashtag" => "#",
        "Compte"  => "from:",
        "Mot-clé" => ""
      );
    }
}
