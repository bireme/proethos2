<?php

namespace Proethos2\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Language
 *
 * @ORM\Table(name="upload_type_translation")
 * @ORM\Entity
 */
class UploadTypeTranslation extends Base
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
     * @ORM\ManyToOne(targetEntity="UploadType") 
     * @ORM\JoinColumn(name="upload_type_id", referencedColumnName="id", nullable=true) 
     * @Assert\NotBlank 
     */ 
    private $upload_type;

    /** 
     * @ORM\ManyToOne(targetEntity="Language") 
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", nullable=true) 
     * @Assert\NotBlank 
     */ 
    private $language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

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
     * Set name
     *
     * @param string $name
     *
     * @return UploadTypeTranslation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set uploadType
     *
     * @param \Proethos2\ModelBundle\Entity\UploadType $uploadType
     *
     * @return UploadTypeTranslation
     */
    public function setUploadType(\Proethos2\ModelBundle\Entity\UploadType $uploadType = null)
    {
        $this->upload_type = $uploadType;

        return $this;
    }

    /**
     * Get uploadType
     *
     * @return \Proethos2\ModelBundle\Entity\UploadType
     */
    public function getUploadType()
    {
        return $this->upload_type;
    }

    /**
     * Set language
     *
     * @param \Proethos2\ModelBundle\Entity\Language $language
     *
     * @return UploadTypeTranslation
     */
    public function setLanguage(\Proethos2\ModelBundle\Entity\Language $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Proethos2\ModelBundle\Entity\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
