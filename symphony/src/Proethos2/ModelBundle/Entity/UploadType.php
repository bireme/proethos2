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
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

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
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var UploadTypeExtension
     * @ORM\ManyToMany(targetEntity="UploadTypeExtension", inversedBy="extensions", cascade={"persist"})
     * @ORM\JoinTable(name="upload_type_upload_type_extension")
     */
    private $extensions;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status = true;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getLocale(){
        return $this->locale;
    }


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
