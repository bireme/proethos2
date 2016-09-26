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
 * @ORM\Table(name="protocol_revision")
 * @ORM\Entity
 */
class ProtocolRevision extends Base
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="members") 
     */ 
    private $member;

    /**
     * @ORM\Column(type="boolean")
     */
    private $answered = false;

    /**
     * @ORM\ManyToOne(targetEntity="Protocol", inversedBy="revision")
     * @ORM\JoinColumn(name="protocol_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $protocol;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_final_revision = false;

    /**
     * @var string
     *
     * @ORM\Column(name="decision", type="string", length=255, nullable=true)
     */
    private $decision;   

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $social_value;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sscientific_validity;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $fair_participant_selection;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $favorable_balance;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $informed_consent;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $respect_for_participants;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $other_comments;

    /**
     * @var string
     *
     * @ORM\Column(name="suggestions", type="string", length=255, nullable=true)
     */
    private $suggestions;   

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
     * Set answered
     *
     * @param boolean $answered
     *
     * @return ProtocolRevision
     */
    public function setAnswered($answered)
    {
        $this->answered = $answered;

        return $this;
    }

    /**
     * Get answered
     *
     * @return boolean
     */
    public function getAnswered()
    {
        return $this->answered;
    }

    /**
     * Set member
     *
     * @param \Proethos2\ModelBundle\Entity\User $member
     *
     * @return ProtocolRevision
     */
    public function setMember(\Proethos2\ModelBundle\Entity\User $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \Proethos2\ModelBundle\Entity\User
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set protocol
     *
     * @param \Proethos2\ModelBundle\Entity\Protocol $protocol
     *
     * @return ProtocolRevision
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
     * Set isFinalRevision
     *
     * @param boolean $isFinalRevision
     *
     * @return ProtocolRevision
     */
    public function setIsFinalRevision($isFinalRevision)
    {
        $this->is_final_revision = $isFinalRevision;

        return $this;
    }

    /**
     * Get isFinalRevision
     *
     * @return boolean
     */
    public function getIsFinalRevision()
    {
        return $this->is_final_revision;
    }

    /**
     * Set decision
     *
     * @param string $decision
     *
     * @return ProtocolRevision
     */
    public function setDecision($decision)
    {
        $this->decision = $decision;

        return $this;
    }

    /**
     * Get decision
     *
     * @return string
     */
    public function getDecision()
    {
        return $this->decision;
    }

    /**
     * Set socialValue
     *
     * @param string $socialValue
     *
     * @return ProtocolRevision
     */
    public function setSocialValue($socialValue)
    {
        $this->social_value = $socialValue;

        return $this;
    }

    /**
     * Get socialValue
     *
     * @return string
     */
    public function getSocialValue()
    {
        return $this->social_value;
    }

    /**
     * Set sscientificValidity
     *
     * @param string $sscientificValidity
     *
     * @return ProtocolRevision
     */
    public function setSscientificValidity($sscientificValidity)
    {
        $this->sscientific_validity = $sscientificValidity;

        return $this;
    }

    /**
     * Get sscientificValidity
     *
     * @return string
     */
    public function getSscientificValidity()
    {
        return $this->sscientific_validity;
    }

    /**
     * Set fairParticipantSelection
     *
     * @param string $fairParticipantSelection
     *
     * @return ProtocolRevision
     */
    public function setFairParticipantSelection($fairParticipantSelection)
    {
        $this->fair_participant_selection = $fairParticipantSelection;

        return $this;
    }

    /**
     * Get fairParticipantSelection
     *
     * @return string
     */
    public function getFairParticipantSelection()
    {
        return $this->fair_participant_selection;
    }

    /**
     * Set favorableBalance
     *
     * @param string $favorableBalance
     *
     * @return ProtocolRevision
     */
    public function setFavorableBalance($favorableBalance)
    {
        $this->favorable_balance = $favorableBalance;

        return $this;
    }

    /**
     * Get favorableBalance
     *
     * @return string
     */
    public function getFavorableBalance()
    {
        return $this->favorable_balance;
    }

    /**
     * Set informedConsent
     *
     * @param string $informedConsent
     *
     * @return ProtocolRevision
     */
    public function setInformedConsent($informedConsent)
    {
        $this->informed_consent = $informedConsent;

        return $this;
    }

    /**
     * Get informedConsent
     *
     * @return string
     */
    public function getInformedConsent()
    {
        return $this->informed_consent;
    }

    /**
     * Set respectForParticipants
     *
     * @param string $respectForParticipants
     *
     * @return ProtocolRevision
     */
    public function setRespectForParticipants($respectForParticipants)
    {
        $this->respect_for_participants = $respectForParticipants;

        return $this;
    }

    /**
     * Get respectForParticipants
     *
     * @return string
     */
    public function getRespectForParticipants()
    {
        return $this->respect_for_participants;
    }

    /**
     * Set otherComments
     *
     * @param string $otherComments
     *
     * @return ProtocolRevision
     */
    public function setOtherComments($otherComments)
    {
        $this->other_comments = $otherComments;

        return $this;
    }

    /**
     * Get otherComments
     *
     * @return string
     */
    public function getOtherComments()
    {
        return $this->other_comments;
    }

    /**
     * Set suggestions
     *
     * @param string $suggestions
     *
     * @return ProtocolRevision
     */
    public function setSuggestions($suggestions)
    {
        $this->suggestions = $suggestions;

        return $this;
    }

    /**
     * Get suggestions
     *
     * @return string
     */
    public function getSuggestions()
    {
        return $this->suggestions;
    }
}
