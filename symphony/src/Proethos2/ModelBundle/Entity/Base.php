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
 
/** 
 * Base abstract class 
 * @ORM\MappedSuperclass 
 */ 
abstract class Base 
{ 
    /** 
     * @var \DateTime 
     * 
     * @ORM\Column(name="created", type="datetime") 
     * @Assert\NotBlank 
     */ 
    private $created; 
 
    /** 
     * @var \DateTime 
     * 
     * @ORM\Column(name="updated", type="datetime") 
     * @Assert\NotBlank 
     */ 
    private $updated; 
 
    /** 
     * Construct 
     */ 
    public function __construct() 
    { 
        $this->created = new \DateTime(); 
        $this->updated = new \DateTime(); 
    } 

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Base
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Base
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
