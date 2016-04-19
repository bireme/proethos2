<?php

namespace Proethos2\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Protocol
 *
 * @ORM\Table(name="protocol")
 * @ORM\Entity
 */
class Protocol extends Base
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** 
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="users") 
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false) 
     * @Assert\NotBlank 
     */ 
    private $owner;

    /** 
     * @var ArrayCollection 
     * 
     * @ORM\OneToMany(targetEntity="Submission", mappedBy="protocol", cascade={"remove"}) 
     */ 
    private $submission;

    /** 
     * Constructor 
     */ 
    public function __construct() 
    { 
         parent::__construct();
    
        $this->submission = new ArrayCollection(); 
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
     * Add submission
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $submission
     *
     * @return Protocol
     */
    public function addSubmission(\Proethos2\ModelBundle\Entity\Submission $submission)
    {
        $this->submission[] = $submission;

        return $this;
    }

    /**
     * Remove submission
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $submission
     */
    public function removeSubmission(\Proethos2\ModelBundle\Entity\Submission $submission)
    {
        $this->submission->removeElement($submission);
    }

    /**
     * Get submission
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubmission()
    {
        return $this->submission;
    }

    /** 
     * @return string 
     */ 
    public function __toString() 
    { 
        return $this->getId(); 
    }

    /**
     * Set owner
     *
     * @param \Proethos2\ModelBundle\Entity\User $owner
     *
     * @return Protocol
     */
    public function setOwner(\Proethos2\ModelBundle\Entity\User $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Proethos2\ModelBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
