<?php

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
     * @ORM\JoinColumn(name="protocol_id", referencedColumnName="id", nullable=true) 
     * @Assert\NotBlank 
     */ 
    private $protocol;

    /** 
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="User") 
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=true) 
     * @Assert\NotBlank 
     */ 
    private $owner;
    
    /**
     * @var string
     *
     * @ORM\Column(name="public_title", type="string", length=255)
     * @Assert\NotBlank 
     */
    private $publicTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="cientific_title", type="string", length=255)
     * @Assert\NotBlank 
     */
    private $cientificTitle;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title_acronyms", type="string", length=255)
     */
    private $titleAcronyms;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_clinical_trial", type="boolean")
     * @Assert\NotBlank 
     */
    private $is_clinical_trial;

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
     * @var string
     *
     * @ORM\Column(type="string", length=1, nullable=true)
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
    
    public function __construct() 
    {
        $this->team = new ArrayCollection(); 
    }

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $inclusion_criteria;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1, nullable=true)
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
     * Set cientificTitle
     *
     * @param string $cientificTitle
     *
     * @return Submission
     */
    public function setCientificTitle($cientificTitle)
    {
        $this->cientificTitle = $cientificTitle;

        return $this;
    }

    /**
     * Get cientificTitle
     *
     * @return string
     */
    public function getCientificTitle()
    {
        return $this->cientificTitle;
    }

    /**
     * Set titleAcronyms
     *
     * @param string $titleAcronyms
     *
     * @return Submission
     */
    public function setTitleAcronyms($titleAcronyms)
    {
        $this->titleAcronyms = $titleAcronyms;

        return $this;
    }

    /**
     * Get titleAcronyms
     *
     * @return string
     */
    public function getTitleAcronyms()
    {
        return $this->titleAcronyms;
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
     * Set gender
     *
     * @param string $gender
     *
     * @return Submission
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
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
     * Set recruitmentStatus
     *
     * @param string $recruitmentStatus
     *
     * @return Submission
     */
    public function setRecruitmentStatus($recruitmentStatus)
    {
        $this->recruitment_status = $recruitmentStatus;

        return $this;
    }

    /**
     * Get recruitmentStatus
     *
     * @return string
     */
    public function getRecruitmentStatus()
    {
        return $this->recruitment_status;
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
}
