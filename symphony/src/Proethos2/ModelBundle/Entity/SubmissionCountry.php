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
 * Country
 *
 * @ORM\Table(name="submission_country")
 * @ORM\Entity
 */
class SubmissionCountry extends Base
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
     * @var Submission
     * 
     * @ORM\ManyToOne(targetEntity="Submission", cascade={"persist"}) 
     * @ORM\JoinColumn(name="submission_id", referencedColumnName="id", nullable=false, onDelete="cascade") 
     * @Assert\NotBlank 
     */ 
    private $submission;

    /** 
     * @var Country
     * 
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="users") 
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="SET NULL") 
     */ 
    private $country;

    /**
     * @var integerS
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank 
     */
    private $participants;

    public function __toString() {
        return $this->getCountry();
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
     * Set participants
     *
     * @param integer $participants
     *
     * @return SubmissionCountry
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;

        return $this;
    }

    /**
     * Get participants
     *
     * @return integer
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * Set submission
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $submission
     *
     * @return SubmissionCountry
     */
    public function setSubmission(\Proethos2\ModelBundle\Entity\Submission $submission)
    {
        $this->submission = $submission;

        return $this;
    }

    /**
     * Get submission
     *
     * @return \Proethos2\ModelBundle\Entity\Submission
     */
    public function getSubmission()
    {
        return $this->submission;
    }

    /**
     * Set country
     *
     * @param \Proethos2\ModelBundle\Entity\Country $country
     *
     * @return SubmissionCountry
     */
    public function setCountry(\Proethos2\ModelBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Proethos2\ModelBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}
