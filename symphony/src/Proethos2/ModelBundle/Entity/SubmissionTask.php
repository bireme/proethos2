<?php

namespace Proethos2\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Country
 *
 * @ORM\Table(name="submission_task")
 * @ORM\Entity
 */
class SubmissionTask extends Base
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
     * @ORM\JoinColumn(name="submission_id", referencedColumnName="id", nullable=false, onDelete="CASCADE") 
     * @Assert\NotBlank 
     */ 
    private $submission;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     * @var Date
     *
     * @ORM\Column(type="date")
     * @Assert\NotBlank 
     */
    private $init;
    
    /**
     * @var Date
     *
     * @ORM\Column(type="date")
     * @Assert\NotBlank 
     */
    private $end;


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
     * @return SubmissionTask
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
     * Set init
     *
     * @param \DateTime $init
     *
     * @return SubmissionTask
     */
    public function setInit($init)
    {
        $this->init = $init;

        return $this;
    }

    /**
     * Get init
     *
     * @return \DateTime
     */
    public function getInit()
    {
        return $this->init;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return SubmissionTask
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set submission
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $submission
     *
     * @return SubmissionTask
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
