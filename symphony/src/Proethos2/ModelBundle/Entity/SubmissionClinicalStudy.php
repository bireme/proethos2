<?php

// This file is part of the ProEthos Software.
//
// Copyright 2013, PAHO. All rights reserved. You can redistribute it and/or modify
// ProEthos under the terms of the ProEthos License as published by PAHO, which
// restricts commercial use of the Software.
//
// ProEthos is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details.
//
// You should have received a copy of the ProEthos License along with the ProEthos
// Software. If not, see
// https://github.com/bireme/proethos2/blob/master/LICENSE.txt


namespace Proethos2\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * SubmissionClinicalTrial
 *
 * @ORM\Table(name="submission_clinical_study")
 * @ORM\Entity
 */
class SubmissionClinicalStudy extends Base
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
     * @ORM\ManyToOne(targetEntity="Submission", inversedBy="clinical_trial")
     * @ORM\JoinColumn(name="submission_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Assert\NotBlank
     */
    private $submission;

    /**
     * @var Gender
     *
     * @ORM\ManyToOne(targetEntity="Gender")
     * @ORM\JoinColumn(name="gender_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $gender;
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sample_size;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $minimum_age;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maximum_age;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $inclusion_criteria;

    /**
     * @var RecruitmentStatus
     *
     * @ORM\ManyToOne(targetEntity="RecruitmentStatus")
     * @ORM\JoinColumn(name="recruitment_status_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $recruitment_status;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $exclusion_criteria;

    /**
     * @var date
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $recruitment_init_date;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=true, length=510)
     * @Assert\NotBlank
     */
    private $description;

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
     * Set sampleSize
     *
     * @param integer $sampleSize
     *
     * @return SubmissionClinicalStudy
     */
    public function setSampleSize($sampleSize)
    {
        $this->sample_size = $sampleSize;

        return $this;
    }

    /**
     * Get sampleSize
     *
     * @return integer
     */
    public function getSampleSize()
    {
        return $this->sample_size;
    }

    /**
     * Set minimumAge
     *
     * @param integer $minimumAge
     *
     * @return SubmissionClinicalStudy
     */
    public function setMinimumAge($minimumAge)
    {
        $this->minimum_age = $minimumAge;

        return $this;
    }

    /**
     * Get minimumAge
     *
     * @return integer
     */
    public function getMinimumAge()
    {
        return $this->minimum_age;
    }

    /**
     * Set maximumAge
     *
     * @param integer $maximumAge
     *
     * @return SubmissionClinicalStudy
     */
    public function setMaximumAge($maximumAge)
    {
        $this->maximum_age = $maximumAge;

        return $this;
    }

    /**
     * Get maximumAge
     *
     * @return integer
     */
    public function getMaximumAge()
    {
        return $this->maximum_age;
    }

    /**
     * Set inclusionCriteria
     *
     * @param string $inclusionCriteria
     *
     * @return SubmissionClinicalStudy
     */
    public function setInclusionCriteria($inclusionCriteria)
    {
        $this->inclusion_criteria = $inclusionCriteria;

        return $this;
    }

    /**
     * Get inclusionCriteria
     *
     * @return string
     */
    public function getInclusionCriteria()
    {
        return $this->inclusion_criteria;
    }

    /**
     * Set exclusionCriteria
     *
     * @param string $exclusionCriteria
     *
     * @return SubmissionClinicalStudy
     */
    public function setExclusionCriteria($exclusionCriteria)
    {
        $this->exclusion_criteria = $exclusionCriteria;

        return $this;
    }

    /**
     * Get exclusionCriteria
     *
     * @return string
     */
    public function getExclusionCriteria()
    {
        return $this->exclusion_criteria;
    }

    /**
     * Set recruitmentInitDate
     *
     * @param \DateTime $recruitmentInitDate
     *
     * @return SubmissionClinicalStudy
     */
    public function setRecruitmentInitDate($recruitmentInitDate)
    {
        $this->recruitment_init_date = $recruitmentInitDate;

        return $this;
    }

    /**
     * Get recruitmentInitDate
     *
     * @return \DateTime
     */
    public function getRecruitmentInitDate()
    {
        return $this->recruitment_init_date;
    }

    /**
     * Set submission
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $submission
     *
     * @return SubmissionClinicalStudy
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

    /**
     * Set gender
     *
     * @param \Proethos2\ModelBundle\Entity\Gender $gender
     *
     * @return SubmissionClinicalStudy
     */
    public function setGender(\Proethos2\ModelBundle\Entity\Gender $gender = null)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return \Proethos2\ModelBundle\Entity\Gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set recruitmentStatus
     *
     * @param \Proethos2\ModelBundle\Entity\RecruitmentStatus $recruitmentStatus
     *
     * @return SubmissionClinicalStudy
     */
    public function setRecruitmentStatus(\Proethos2\ModelBundle\Entity\RecruitmentStatus $recruitmentStatus = null)
    {
        $this->recruitment_status = $recruitmentStatus;

        return $this;
    }

    /**
     * Get recruitmentStatus
     *
     * @return \Proethos2\ModelBundle\Entity\RecruitmentStatus
     */
    public function getRecruitmentStatus()
    {
        return $this->recruitment_status;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return SubmissionClinicalStudy
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
}
