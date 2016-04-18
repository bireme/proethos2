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
     * @param $created 
     */ 
    public function setCreatedAt($created) 
    { 
        $this->created = $created; 
    } 
 
    /** 
     * Get CreatedAt 
     * 
     * @return \DateTime 
     */ 
    public function getCreatedAt() 
    { 
        return $this->created; 
    } 
 
    /** 
     * Set UpdatedAt 
     * 
     * @param \DateTime $updated 
     */ 
    public function setUpdatedAt($updated) 
    { 
        $this->updated = $updated; 
    } 
    /** 
     * Get UpdateAt 
     * 
     * @return \DateTime 
     */ 
    public function getUpdatedAt() 
    { 
        return $this->updated; 
    } 
}