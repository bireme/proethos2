<?php

namespace Proethos2\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @var Protocol
     * 
     * @ORM\ManyToOne(targetEntity="Protocol", inversedBy="submissions") 
     * @ORM\JoinColumn(name="protocol_id", referencedColumnName="id", nullable=false) 
     * @Assert\NotBlank 
     */ 
    private $protocol;

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
}
