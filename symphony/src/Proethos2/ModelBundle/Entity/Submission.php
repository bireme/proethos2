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
     * @ORM\JoinColumn(name="protocol_id", referencedColumnName="id", nullable=false) 
     * @Assert\NotBlank 
     */ 
    private $protocol;

    /** 
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="User") 
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false) 
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
     * @ORM\Column(type="text", nullable=false)
     */
    private $abstract;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $keywords;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $introduction;
    
    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $justification;
    
    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $goals;
    
    public function __construct() 
    {
        $this->team = new ArrayCollection(); 
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
}
