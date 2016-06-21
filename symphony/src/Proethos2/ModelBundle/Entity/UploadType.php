<?php

namespace Proethos2\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use Cocur\Slugify\Slugify;

/**
 * UploadType
 *
 * @ORM\Table(name="upload_type")
 * @ORM\Entity
 */
class UploadType extends Base
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;    

    /**
     * @var UploadTypeExtension
     * @ORM\ManyToMany(targetEntity="UploadTypeExtension", inversedBy="extensions")
     * @ORM\JoinTable(name="upload_type_upload_type_extension")
     */
    private $extensions;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status = true;    

    
    public function __toString() {
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     *
     * @return UploadType
     */
    public function setName($name)
    {
        $slugify = new Slugify();

        $this->name = $name;
        $this->setSlug($slugify->slugify($name));

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
     * Add extension
     *
     * @param \Proethos2\ModelBundle\Entity\UploadTypeExtension $extension
     *
     * @return UploadType
     */
    public function addExtension(\Proethos2\ModelBundle\Entity\UploadTypeExtension $extension)
    {
        $this->extensions[] = $extension;

        return $this;
    }

    /**
     * Remove extension
     *
     * @param \Proethos2\ModelBundle\Entity\UploadTypeExtension $extension
     */
    public function removeExtension(\Proethos2\ModelBundle\Entity\UploadTypeExtension $extension)
    {
        $this->extensions->removeElement($extension);
    }

    /**
     * Get extensions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return UploadType
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return UploadType
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
}
