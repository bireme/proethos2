<?php

namespace Proethos2\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Country
 *
 * @ORM\Table(name="submission_cost")
 * @ORM\Entity
 */
class SubmissionCost extends Base
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
     * @var Submission
     * 
     * @ORM\ManyToOne(targetEntity="Submission") 
     * @ORM\JoinColumn(name="submission_id", referencedColumnName="id", nullable=false) 
     * @Assert\NotBlank 
     */ 
    private $submission;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank 
     */
    private $quantity;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float")
     * @Assert\NotBlank 
     */
    private $unit_cost;

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
     * Set description
     *
     * @param string $description
     *
     * @return SubmissionCost
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return SubmissionCost
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set unitCost
     *
     * @param float $unitCost
     *
     * @return SubmissionCost
     */
    public function setUnitCost($unitCost)
    {
        $this->unit_cost = $unitCost;

        return $this;
    }

    /**
     * Get unitCost
     *
     * @return float
     */
    public function getUnitCost()
    {
        return $this->unit_cost;
    }

    /**
     * Set submission
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $submission
     *
     * @return SubmissionCost
     */
    public function setSubmission(\Proethos2\ModelBundle\Entity\Submission $submission)
    {
        $this->submission = $submission;

        return $this;
    }

    /**
     * Get submission
     *
     * @return \Proethos2\ModelBundle\Entity\Submission
     */
    public function getSubmission()
    {
        return $this->submission;
    }
}
