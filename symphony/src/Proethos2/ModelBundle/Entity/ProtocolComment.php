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

use Cocur\Slugify\Slugify;

/**
 * ProtocolComment
 *
 * @ORM\Table(name="protocol_comment")
 * @ORM\Entity
 */
class ProtocolComment extends Base
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
     * @ORM\Column(type="text")
     */
    private $message;

    /** 
     * @ORM\ManyToOne(targetEntity="Protocol") 
     * @ORM\JoinColumn(name="protocol_id", referencedColumnName="id", onDelete="CASCADE") 
     */ 
    private $protocol;

    /** 
     * @ORM\ManyToOne(targetEntity="User") 
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE") 
     */ 
    private $owner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_confidential = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=1023, nullable=true)
     */
    private $filepath;

    public function getRealFilename() {
        $filename = explode('_', $this->getFilename(), 2);
        return end($filename);
    }

    public function getUploadDirectory() {
        $upload_directory = __DIR__.'/../../../../uploads/comments';
        $upload_directory = $upload_directory . "/" . str_pad($this->getProtocol()->getId(), 5, '0', STR_PAD_LEFT);

        if(!is_dir($upload_directory)) {
            mkdir($upload_directory);
        }

        return $upload_directory;
    }

    public function setFile($file) {
        $slugify = new Slugify();
        $upload_directory = $this->getUploadDirectory();

        $filename_without_extension = str_replace("." . $file->getClientOriginalExtension(), "", $file->getClientOriginalName());
        $filename = uniqid() . '_' . $slugify->slugify($filename_without_extension) . "." . $file->getClientOriginalExtension();
        $filepath = $upload_directory . "/" . $filename;
        $file = $file->move($upload_directory, $filename);

        $this->setFilename($filename);
        $this->setFilepath($filepath);

        return $this;
    }

    public function getUri() {
        return "/uploads/comments/" . str_pad($this->getProtocol()->getId(), 5, '0', STR_PAD_LEFT) . "/" . $this->getFilename();
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
     * Set message
     *
     * @param string $message
     *
     * @return ProtocolComment
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set protocol
     *
     * @param \Proethos2\ModelBundle\Entity\Protocol $protocol
     *
     * @return ProtocolComment
     */
    public function setProtocol(\Proethos2\ModelBundle\Entity\Protocol $protocol = null)
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
     * Set owner
     *
     * @param \Proethos2\ModelBundle\Entity\User $owner
     *
     * @return ProtocolComment
     */
    public function setOwner(\Proethos2\ModelBundle\Entity\User $owner = null)
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
     * Set isConfidential
     *
     * @param boolean $isConfidential
     *
     * @return ProtocolComment
     */
    public function setIsConfidential($isConfidential)
    {
        $this->is_confidential = $isConfidential;

        return $this;
    }

    /**
     * Get isConfidential
     *
     * @return boolean
     */
    public function getIsConfidential()
    {
        return $this->is_confidential;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return ProtocolComment
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return ProtocolComment
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set filepath
     *
     * @param string $filepath
     *
     * @return ProtocolComment
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;

        return $this;
    }

    /**
     * Get filepath
     *
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
    }
}
