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
 * Submission
 *
 * @ORM\Table(name="submission")
 * @ORM\Entity(repositoryClass="Proethos2\ModelBundle\Repository\SubmissionRepository")
 */
class Submission extends Base
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
     * @var Protocol
     *
     * @ORM\ManyToOne(targetEntity="Protocol", inversedBy="submissions")
     * @ORM\JoinColumn(name="protocol_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * @Assert\NotBlank
     */
    private $protocol;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", nullable=true, length=255)
     */
    private $language;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_translation", type="boolean")
     */
    private $is_translation = false;

    /**
     * @var Submission
     *
     * @ORM\ManyToOne(targetEntity="Submission")
     * @ORM\JoinColumn(name="original_submission_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $original_submission;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Assert\NotBlank
     */
    private $owner;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Submission", mappedBy="original_submission", cascade={"remove"})
     */
    private $translations;

    /**
     * @ORM\Column(name="number", type="integer")
     * @Assert\NotBlank
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="public_title", type="string", length=510)
     * @Assert\NotBlank
     */
    private $publicTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="scientific_title", type="string", length=510)
     * @Assert\NotBlank
     */
    private $scientificTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="title_acronym", type="string", length=255)
     */
    private $titleAcronym;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_clinical_trial", type="boolean")
     * @Assert\NotBlank
     */
    private $is_clinical_trial;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_sent", type="boolean")
     * @Assert\NotBlank
     */
    private $is_sent = false;

    /**
     * @var Team
     * @ORM\ManyToMany(targetEntity="User", inversedBy="users")
     * @ORM\JoinTable(name="submission_user")
     */
    private $team;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $abstract;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keywords;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $introduction;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $justification;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $goals;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $study_design;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $health_condition;

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
     * @var SubmissionCountry
     * @ORM\OneToMany(targetEntity="SubmissionCountry", mappedBy="submission", cascade={"persist"})
     * @ORM\JoinTable(name="submission_country")
     */
    private $country;

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
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $interventions;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $primary_outcome;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $secondary_outcome;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $general_procedures;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $analysis_plan;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $ethical_considerations;

    /**
     * @var SubmissionCost
     * @ORM\OneToMany(targetEntity="SubmissionCost", mappedBy="submission", cascade={"persist"})
     * @ORM\JoinTable(name="submission_cost")
     */
    private $budget;

    /**
     * @var SubmissionClinicalTrial
     * @ORM\OneToMany(targetEntity="SubmissionClinicalTrial", mappedBy="submission", cascade={"persist"})
     * @ORM\JoinTable(name="submission_clinical_trial")
     */
    private $clinical_trial;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $clinical_trial_secondary;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $funding_source;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $primary_sponsor;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $secondary_sponsor;

    /**
     * @var SubmissionTask
     * @ORM\OneToMany(targetEntity="SubmissionTask", mappedBy="submission", cascade={"persist"})
     * @ORM\JoinTable(name="submission_task")
     */
    private $schedule;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $bibliography;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $sscientific_contact;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $prior_ethical_approval;

    /**
     * @var SubmissionUpload
     * @ORM\OneToMany(targetEntity="SubmissionUpload", mappedBy="submission", cascade={"persist"})
     * @ORM\JoinTable(name="submission_upload")
     */
    private $attachments;

    /**
     * @var string`
     *
     * @ORM\Column(name="internal_protocol_number", type="string", length=510, nullable=true)
     */
    private $internal_protocol_number;

    public function __construct() {

        $this->country = new ArrayCollection();
        $this->budget = new ArrayCollection();
        $this->clinical_trial = new ArrayCollection();
        $this->schedule = new ArrayCollection();
        $this->attachments = new ArrayCollection();

        // call Grandpa's constructor
        parent::__construct();
    }


    public function __clone() {

        $this->setCreated(new \Datetime());
        $this->setUpdated(new \Datetime());
        $this->setIsSended(false);

        foreach(array('country', 'budget', 'clinical_trial', 'schedule', 'attachments') as $attribute) {

            $mClone = new ArrayCollection();
            foreach ($this->$attribute as $item) {
                $itemClone = clone $item;
                $itemClone->setSubmission($this);
                $mClone->add($itemClone);
            }

            $this->$attribute = $mClone;
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
     * Set publicTitle
     *
     * @param string $publicTitle
     *
     * @return Submission
     */
    public function setPublicTitle($publicTitle)
    {
        $this->publicTitle = $publicTitle;

        return $this;
    }

    /**
     * Get publicTitle
     *
     * @return string
     */
    public function getPublicTitle()
    {
        return $this->publicTitle;
    }

    /**
     * Set protocol
     *
     * @param \Proethos2\ModelBundle\Entity\Protocol $protocol
     *
     * @return Submission
     */
    public function setProtocol(\Proethos2\ModelBundle\Entity\Protocol $protocol)
    {
        $this->protocol = $protocol;

        return $this;
    }

    /**
     * Get protocol
     *
     * @return \Proethos2\ModelBundle\Entity\Protocol
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Set scientificTitle
     *
     * @param string $scientificTitle
     *
     * @return Submission
     */
    public function setScientificTitle($scientificTitle)
    {
        $this->scientificTitle = $scientificTitle;

        return $this;
    }

    /**
     * Get scientificTitle
     *
     * @return string
     */
    public function getScientificTitle()
    {
        return $this->scientificTitle;
    }

    /**
     * Set titleAcronym
     *
     * @param string $titleAcronym
     *
     * @return Submission
     */
    public function setTitleAcronym($titleAcronym)
    {
        $this->titleAcronym = $titleAcronym;

        return $this;
    }

    /**
     * Get titleAcronym
     *
     * @return string
     */
    public function getTitleAcronym()
    {
        return $this->titleAcronym;
    }

    /**
     * Set isClinicalTrial
     *
     * @param string $isClinicalTrial
     *
     * @return Submission
     */
    public function setIsClinicalTrial($isClinicalTrial)
    {
        $this->is_clinical_trial = $isClinicalTrial;

        return $this;
    }

    /**
     * Get isClinicalTrial
     *
     * @return string
     */
    public function getIsClinicalTrial()
    {
        return $this->is_clinical_trial;
    }

    /**
     * Set owner
     *
     * @param \Proethos2\ModelBundle\Entity\User $owner
     *
     * @return Submission
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

    /**
     * Add team
     *
     * @param \Proethos2\ModelBundle\Entity\User $team
     *
     * @return Submission
     */
    public function addTeam(\Proethos2\ModelBundle\Entity\User $team)
    {
        $this->team[] = $team;

        return $this;
    }

    /**
     * Remove team
     *
     * @param \Proethos2\ModelBundle\Entity\User $team
     */
    public function removeTeam(\Proethos2\ModelBundle\Entity\User $team)
    {
        $this->team->removeElement($team);
    }

    /**
     * Get team
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Get total team
     *
     * @return int
     */
    public function getTotalTeam()
    {
        return count($this->team) + 1;
    }

    public function isOwner(User $user)
    {
        if($this->getTeam()->contains($user)) {
            return true;
        }

        if($user == $this->getOwner()) {
            return true;
        }

        return false;
    }

    /**
     * Set abstract
     *
     * @param string $abstract
     *
     * @return Submission
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;

        return $this;
    }

    /**
     * Get abstract
     *
     * @return string
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     *
     * @return Submission
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set introduction
     *
     * @param string $introduction
     *
     * @return Submission
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;

        return $this;
    }

    /**
     * Get introduction
     *
     * @return string
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * Set justification
     *
     * @param string $justification
     *
     * @return Submission
     */
    public function setJustification($justification)
    {
        $this->justification = $justification;

        return $this;
    }

    /**
     * Get justification
     *
     * @return string
     */
    public function getJustification()
    {
        return $this->justification;
    }

    /**
     * Set goals
     *
     * @param string $goals
     *
     * @return Submission
     */
    public function setGoals($goals)
    {
        $this->goals = $goals;

        return $this;
    }

    /**
     * Get goals
     *
     * @return string
     */
    public function getGoals()
    {
        return $this->goals;
    }

    /**
     * Set studyDesign
     *
     * @param string $studyDesign
     *
     * @return Submission
     */
    public function setStudyDesign($studyDesign)
    {
        $this->study_design = $studyDesign;

        return $this;
    }

    /**
     * Get studyDesign
     *
     * @return string
     */
    public function getStudyDesign()
    {
        return $this->study_design;
    }

    /**
     * Set healthCondition
     *
     * @param string $healthCondition
     *
     * @return Submission
     */
    public function setHealthCondition($healthCondition)
    {
        $this->health_condition = $healthCondition;

        return $this;
    }

    /**
     * Get healthCondition
     *
     * @return string
     */
    public function getHealthCondition()
    {
        return $this->health_condition;
    }

    /**
     * Set sampleSize
     *
     * @param integer $sampleSize
     *
     * @return Submission
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
     * @return Submission
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
     * @return Submission
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
     * @return Submission
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
     * @return Submission
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
     * @return Submission
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
     * Set interventions
     *
     * @param string $interventions
     *
     * @return Submission
     */
    public function setInterventions($interventions)
    {
        $this->interventions = $interventions;

        return $this;
    }

    /**
     * Get interventions
     *
     * @return string
     */
    public function getInterventions()
    {
        return $this->interventions;
    }

    /**
     * Set primaryOutcome
     *
     * @param string $primaryOutcome
     *
     * @return Submission
     */
    public function setPrimaryOutcome($primaryOutcome)
    {
        $this->primary_outcome = $primaryOutcome;

        return $this;
    }

    /**
     * Get primaryOutcome
     *
     * @return string
     */
    public function getPrimaryOutcome()
    {
        return $this->primary_outcome;
    }

    /**
     * Set secondaryOutcome
     *
     * @param string $secondaryOutcome
     *
     * @return Submission
     */
    public function setSecondaryOutcome($secondaryOutcome)
    {
        $this->secondary_outcome = $secondaryOutcome;

        return $this;
    }

    /**
     * Get secondaryOutcome
     *
     * @return string
     */
    public function getSecondaryOutcome()
    {
        return $this->secondary_outcome;
    }

    /**
     * Set generalProcedures
     *
     * @param string $generalProcedures
     *
     * @return Submission
     */
    public function setGeneralProcedures($generalProcedures)
    {
        $this->general_procedures = $generalProcedures;

        return $this;
    }

    /**
     * Get generalProcedures
     *
     * @return string
     */
    public function getGeneralProcedures()
    {
        return $this->general_procedures;
    }

    /**
     * Set analysisPlan
     *
     * @param string $analysisPlan
     *
     * @return Submission
     */
    public function setAnalysisPlan($analysisPlan)
    {
        $this->analysis_plan = $analysisPlan;

        return $this;
    }

    /**
     * Get analysisPlan
     *
     * @return string
     */
    public function getAnalysisPlan()
    {
        return $this->analysis_plan;
    }

    /**
     * Set ethicalConsiderations
     *
     * @param string $ethicalConsiderations
     *
     * @return Submission
     */
    public function setEthicalConsiderations($ethicalConsiderations)
    {
        $this->ethical_considerations = $ethicalConsiderations;

        return $this;
    }

    /**
     * Get ethicalConsiderations
     *
     * @return string
     */
    public function getEthicalConsiderations()
    {
        return $this->ethical_considerations;
    }

    /**
     * Add country
     *
     * @param \Proethos2\ModelBundle\Entity\SubmissionCountry $country
     *
     * @return Submission
     */
    public function addCountry(\Proethos2\ModelBundle\Entity\SubmissionCountry $country)
    {
        $this->country[] = $country;

        return $this;
    }

    /**
     * Remove country
     *
     * @param \Proethos2\ModelBundle\Entity\SubmissionCountry $country
     */
    public function removeCountry(\Proethos2\ModelBundle\Entity\SubmissionCountry $country)
    {
        $this->country->removeElement($country);
    }

    /**
     * Get country
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set fundingSource
     *
     * @param string $fundingSource
     *
     * @return Submission
     */
    public function setFundingSource($fundingSource)
    {
        $this->funding_source = $fundingSource;

        return $this;
    }

    /**
     * Get fundingSource
     *
     * @return string
     */
    public function getFundingSource()
    {
        return $this->funding_source;
    }

    /**
     * Set primarySponsor
     *
     * @param string $primarySponsor
     *
     * @return Submission
     */
    public function setPrimarySponsor($primarySponsor)
    {
        $this->primary_sponsor = $primarySponsor;

        return $this;
    }

    /**
     * Get primarySponsor
     *
     * @return string
     */
    public function getPrimarySponsor()
    {
        return $this->primary_sponsor;
    }

    /**
     * Set secondarySponsor
     *
     * @param string $secondarySponsor
     *
     * @return Submission
     */
    public function setSecondarySponsor($secondarySponsor)
    {
        $this->secondary_sponsor = $secondarySponsor;

        return $this;
    }

    /**
     * Get secondarySponsor
     *
     * @return string
     */
    public function getSecondarySponsor()
    {
        return $this->secondary_sponsor;
    }

    /**
     * Add budget
     *
     * @param \Proethos2\ModelBundle\Entity\SubmissionCost $budget
     *
     * @return Submission
     */
    public function addBudget(\Proethos2\ModelBundle\Entity\SubmissionCost $budget)
    {
        $this->budget[] = $budget;

        return $this;
    }

    /**
     * Remove budget
     *
     * @param \Proethos2\ModelBundle\Entity\SubmissionCost $budget
     */
    public function removeBudget(\Proethos2\ModelBundle\Entity\SubmissionCost $budget)
    {
        $this->budget->removeElement($budget);
    }

    /**
     * Get budget
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Add schedule
     *
     * @param \Proethos2\ModelBundle\Entity\SubmissionTask $schedule
     *
     * @return Submission
     */
    public function addSchedule(\Proethos2\ModelBundle\Entity\SubmissionTask $schedule)
    {
        $this->schedule[] = $schedule;

        return $this;
    }

    /**
     * Remove schedule
     *
     * @param \Proethos2\ModelBundle\Entity\SubmissionTask $schedule
     */
    public function removeSchedule(\Proethos2\ModelBundle\Entity\SubmissionTask $schedule)
    {
        $this->schedule->removeElement($schedule);
    }

    /**
     * Get schedule
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * Set bibliography
     *
     * @param string $bibliography
     *
     * @return Submission
     */
    public function setBibliography($bibliography)
    {
        $this->bibliography = $bibliography;

        return $this;
    }

    /**
     * Get bibliography
     *
     * @return string
     */
    public function getBibliography()
    {
        return $this->bibliography;
    }

    /**
     * Set sscientificContact
     *
     * @param string $sscientificContact
     *
     * @return Submission
     */
    public function setSscientificContact($sscientificContact)
    {
        $this->sscientific_contact = $sscientificContact;

        return $this;
    }

    /**
     * Get sscientificContact
     *
     * @return string
     */
    public function getSscientificContact()
    {
        return $this->sscientific_contact;
    }

    /**
     * Set priorEthicalApproval
     *
     * @param boolean $priorEthicalApproval
     *
     * @return Submission
     */
    public function setPriorEthicalApproval($priorEthicalApproval)
    {
        $this->prior_ethical_approval = $priorEthicalApproval;

        return $this;
    }

    /**
     * Get priorEthicalApproval
     *
     * @return boolean
     */
    public function getPriorEthicalApproval()
    {
        return $this->prior_ethical_approval;
    }

    /**
     * Add attachment
     *
     * @param \Proethos2\ModelBundle\Entity\SubmissionUpload $attachment
     *
     * @return Submission
     */
    public function addAttachment(\Proethos2\ModelBundle\Entity\SubmissionUpload $attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Remove attachment
     *
     * @param \Proethos2\ModelBundle\Entity\SubmissionUpload $attachment
     */
    public function removeAttachment(\Proethos2\ModelBundle\Entity\SubmissionUpload $attachment)
    {
        $this->attachments->removeElement($attachment);
    }

    /**
     * Get attachments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Set gender
     *
     * @param \Proethos2\ModelBundle\Entity\Gender $gender
     *
     * @return Submission
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
     * @return Submission
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
     * Add clinicalTrial
     *
     * @param \Proethos2\ModelBundle\Entity\SubmissionClinicalTrial $clinicalTrial
     *
     * @return Submission
     */
    public function addClinicalTrial(\Proethos2\ModelBundle\Entity\SubmissionClinicalTrial $clinicalTrial)
    {
        $this->clinical_trial[] = $clinicalTrial;

        return $this;
    }

    /**
     * Remove clinicalTrial
     *
     * @param \Proethos2\ModelBundle\Entity\SubmissionClinicalTrial $clinicalTrial
     */
    public function removeClinicalTrial(\Proethos2\ModelBundle\Entity\SubmissionClinicalTrial $clinicalTrial)
    {
        $this->clinical_trial->removeElement($clinicalTrial);
    }

    /**
     * Get clinicalTrial
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClinicalTrial()
    {
        return $this->clinical_trial;
    }

    /**
     * Set clinicalTrialSecondary
     *
     * @param string $clinicalTrialSecondary
     *
     * @return Submission
     */
    public function setClinicalTrialSecondary($clinicalTrialSecondary)
    {
        $this->clinical_trial_secondary = $clinicalTrialSecondary;

        return $this;
    }

    /**
     * Get clinicalTrialSecondary
     *
     * @return string
     */
    public function getClinicalTrialSecondary()
    {
        return $this->clinical_trial_secondary;
    }

    /**
     * Set isSended
     *
     * @param boolean $isSended
     *
     * @return Submission
     */
    public function setIsSended($isSended)
    {
        $this->is_sent = $isSended;

        return $this;
    }

    /**
     * Get isSended
     *
     * @return boolean
     */
    public function getIsSended()
    {
        return $this->is_sent;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return Submission
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
     * Set language
     *
     * @param string $language
     *
     * @return Submission
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set isSent
     *
     * @param boolean $isSent
     *
     * @return Submission
     */
    public function setIsSent($isSent)
    {
        $this->is_sent = $isSent;

        return $this;
    }

    /**
     * Get isSent
     *
     * @return boolean
     */
    public function getIsSent()
    {
        return $this->is_sent;
    }

    /**
     * Add tranlsation
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $tranlsation
     *
     * @return Submission
     */
    public function addTranlsation(\Proethos2\ModelBundle\Entity\Submission $tranlsation)
    {
        $this->translations[] = $tranlsation;

        return $this;
    }

    /**
     * Remove tranlsation
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $tranlsation
     */
    public function removeTranlsation(\Proethos2\ModelBundle\Entity\Submission $tranlsation)
    {
        $this->translations->removeElement($tranlsation);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Get total translations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTotalTranslations()
    {
        return count($this->getTranslations());
    }

    /**
     * Set isTranslation
     *
     * @param boolean $isTranslation
     *
     * @return Submission
     */
    public function setIsTranslation($isTranslation)
    {
        $this->is_translation = $isTranslation;

        return $this;
    }

    /**
     * Get isTranslation
     *
     * @return boolean
     */
    public function getIsTranslation()
    {
        return $this->is_translation;
    }

    /**
     * Set originalSubmission
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $originalSubmission
     *
     * @return Submission
     */
    public function setOriginalSubmission(\Proethos2\ModelBundle\Entity\Submission $originalSubmission = null)
    {
        $this->original_submission = $originalSubmission;

        return $this;
    }

    /**
     * Get originalSubmission
     *
     * @return \Proethos2\ModelBundle\Entity\Submission
     */
    public function getOriginalSubmission()
    {
        return $this->original_submission;
    }

    /**
     * can be edited?
     *
     * @return boolean
     */
    public function getCanBeEdited()
    {
        if($this->getProtocol()->getStatus() == "D" or $this->getProtocol()->getStatus() == "R") {
            return true;
        } else {
            if($this->getProtocol()->getIsMigrated()) {
                return true;
            }
            return false;
        }
    }

    /**
     * Set internalProtocolNumber
     *
     * @param string $internalProtocolNumber
     *
     * @return Submission
     */
    public function setInternalProtocolNumber($internalProtocolNumber)
    {
        $this->internal_protocol_number = $internalProtocolNumber;

        return $this;
    }

    /**
     * Get internalProtocolNumber
     *
     * @return string
     */
    public function getInternalProtocolNumber()
    {
        return $this->internal_protocol_number;
    }

    /**
     * Add translation
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $translation
     *
     * @return Submission
     */
    public function addTranslation(\Proethos2\ModelBundle\Entity\Submission $translation)
    {
        $this->translations[] = $translation;

        return $this;
    }

    /**
     * Remove translation
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $translation
     */
    public function removeTranslation(\Proethos2\ModelBundle\Entity\Submission $translation)
    {
        $this->translations->removeElement($translation);
    }
}
