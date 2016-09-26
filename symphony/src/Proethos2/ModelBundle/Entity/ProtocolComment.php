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
     * @ORM\Column(type="boolean")
     */
    private $is_confidential = false;

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
}
