<?php 
 
namespace Proethos2\ModelBundle\Entity; 
 
use Doctrine\ORM\Mapping as ORM; 
use Symfony\Component\Validator\Constraints as Assert; 
 
/** 
 * Base abstract class 
 * @ORM\MappedSuperclass 
 */ 
abstract class Base 
{ 
    /** 
     * @var \DateTime 
     * 
     * @ORM\Column(name="created", type="datetime") 
     * @Assert\NotBlank 
     */ 
    private $created; 
 
    /** 
     * @var \DateTime 
     * 
     * @ORM\Column(name="updated", type="datetime") 
     * @Assert\NotBlank 
     */ 
    private $updated; 
 
    /** 
     * Construct 
     */ 
    public function __construct() 
    { 
        $this->created = new \DateTime(); 
        $this->updated = new \DateTime(); 
    } 

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Base
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Base
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
