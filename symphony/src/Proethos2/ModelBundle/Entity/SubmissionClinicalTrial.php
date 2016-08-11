<?php

namespace Proethos2\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * SubmissionClinicalTrial
 *
 * @ORM\Table(name="submission_clinical_trial")
 * @ORM\Entity
 */
class SubmissionClinicalTrial extends Base
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
     * @var ClinicalTrialName
     * 
     * @ORM\ManyToOne(targetEntity="ClinicalTrialName") 
     * @ORM\JoinColumn(name="clinical_trial_name_id", referencedColumnName="id", onDelete="SET NULL") 
     */ 
    private $name;

    /** 
     * @var Submission
     * 
     * @ORM\ManyToOne(targetEntity="Submission", inversedBy="clinical_trial") 
     * @ORM\JoinColumn(name="submission_id", referencedColumnName="id", nullable=false, onDelete="CASCADE") 
     * @Assert\NotBlank 
     */ 
    private $submission;

    /**
     * @var number
     *
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @var date
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;



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
     * Set number
     *
     * @param integer $number
     *
     * @return SubmissionClinicalTrial
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return SubmissionClinicalTrial
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set name
     *
     * @param \Proethos2\ModelBundle\Entity\ClinicalTrialName $name
     *
     * @return SubmissionClinicalTrial
     */
    public function setName(\Proethos2\ModelBundle\Entity\ClinicalTrialName $name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return \Proethos2\ModelBundle\Entity\ClinicalTrialName
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set submission
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $submission
     *
     * @return SubmissionClinicalTrial
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
