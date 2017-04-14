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
 * Protocol
 *
 * @ORM\Table(name="protocol")
 * @ORM\Entity
 */
class Protocol extends Base
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
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $migrated_id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\NotBlank
     */
    private $code;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="users")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Assert\NotBlank
     */
    private $owner;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Submission", mappedBy="protocol", cascade={"remove"})
     */
    private $submission;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ProtocolHistory", mappedBy="protocol", cascade={"remove"})
     */
    private $history;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ProtocolComment", mappedBy="protocol", cascade={"remove"})
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\NotBlank
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Submission")
     * @ORM\JoinColumn(name="main_submission_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $main_submission;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $reject_reason;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $committee_screening;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $opinion_required = 0;

    /**
     * @ORM\Column(name="date_informed", type="datetime", nullable=true)
     */
    private $date_informed;

    /**
     * @ORM\Column(name="updated_in", type="datetime", nullable=true)
     */
    private $updated_in;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $revised_in;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $decision_in;

    /**
     * @var ProtocolRevision
     *
     * @ORM\OneToMany(targetEntity="ProtocolRevision", mappedBy="protocol", cascade={"remove"})
     */
    private $revision;

    /**
     * @ORM\ManyToOne(targetEntity="Meeting", inversedBy="protocols")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $meeting;

    /**
     * @var MonitoringAction
     *
     * @ORM\ManyToOne(targetEntity="MonitoringAction", inversedBy="protocols")
     * @ORM\JoinColumn(name="monitoring_action_id", referencedColumnName="id", onDelete="SET NULL")
     * @Assert\NotBlank
     */
    private $monitoring_action;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $monitoring_action_next_date;

    /**
     * Constructor
     */
    public function __construct()
    {
         parent::__construct();

        $this->submission = new ArrayCollection();
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
     * Add submission
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $submission
     *
     * @return Protocol
     */
    public function addSubmission(\Proethos2\ModelBundle\Entity\Submission $submission)
    {
        $this->submission[] = $submission;

        return $this;
    }

    /**
     * Remove submission
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $submission
     */
    public function removeSubmission(\Proethos2\ModelBundle\Entity\Submission $submission)
    {
        $this->submission->removeElement($submission);
    }

    /**
     * Get submission
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubmission()
    {
        return $this->submission;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if($this->getCode()) {

            return (string) $this->getCode();
        }
        return (string) $this->getId();
    }

    /**
     * Set owner
     *
     * @param \Proethos2\ModelBundle\Entity\User $owner
     *
     * @return Protocol
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
     * Set status
     *
     * @param string $status
     *
     * @return Protocol
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function getStatusLabel()
    {
        switch ($this->status) {
            case 'D': return "Draft"; break;
            case 'S': return "Submitted"; break;
            case 'R': return "Rejected"; break;
            case 'I': return "Waiting for initial evaluation"; break;
            case 'E': return "Waiting for Committee"; break;
            case 'H': return "Scheduled"; break;
            case 'F': return "Exempted"; break;
            case 'A': return "Approved"; break;
            case 'N': return "Not approved"; break;
            case 'C': return "Conditional Approval"; break;
            case 'X': return "Expedite Approval"; break;
        }
        return $this->status;
    }

    /**
     * Set mainSubmission
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $mainSubmission
     *
     * @return Protocol
     */
    public function setMainSubmission(\Proethos2\ModelBundle\Entity\Submission $mainSubmission = null)
    {
        $this->main_submission = $mainSubmission;

        return $this;
    }

    /**
     * Get mainSubmission
     *
     * @return \Proethos2\ModelBundle\Entity\Submission
     */
    public function getMainSubmission()
    {
        return $this->main_submission;
    }

    /**
     * Set dateInformed
     *
     * @param \DateTime $dateInformed
     *
     * @return Protocol
     */
    public function setDateInformed($dateInformed)
    {
        $this->date_informed = $dateInformed;

        return $this;
    }

    /**
     * Get dateInformed
     *
     * @return \DateTime
     */
    public function getDateInformed()
    {
        return $this->date_informed;
    }

    /**
     * Add history
     *
     * @param \Proethos2\ModelBundle\Entity\ProtocolHistory $history
     *
     * @return Protocol
     */
    public function addHistory(\Proethos2\ModelBundle\Entity\ProtocolHistory $history)
    {
        $this->history[] = $history;

        return $this;
    }

    /**
     * Remove history
     *
     * @param \Proethos2\ModelBundle\Entity\ProtocolHistory $history
     */
    public function removeHistory(\Proethos2\ModelBundle\Entity\ProtocolHistory $history)
    {
        $this->history->removeElement($history);
    }

    /**
     * Get history
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Add comment
     *
     * @param \Proethos2\ModelBundle\Entity\ProtocolComment $comment
     *
     * @return Protocol
     */
    public function addComment(\Proethos2\ModelBundle\Entity\ProtocolComment $comment)
    {
        $this->comment[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \Proethos2\ModelBundle\Entity\ProtocolComment $comment
     */
    public function removeComment(\Proethos2\ModelBundle\Entity\ProtocolComment $comment)
    {
        $this->comment->removeElement($comment);
    }

    /**
     * Get comment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set rejectReason
     *
     * @param string $rejectReason
     *
     * @return Protocol
     */
    public function setRejectReason($rejectReason)
    {
        $this->reject_reason = $rejectReason;

        return $this;
    }

    /**
     * Get rejectReason
     *
     * @return string
     */
    public function getRejectReason()
    {
        return $this->reject_reason;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Protocol
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set committeeScreening
     *
     * @param string $committeeScreening
     *
     * @return Protocol
     */
    public function setCommitteeScreening($committeeScreening)
    {
        $this->committee_screening = $committeeScreening;

        return $this;
    }

    /**
     * Get committeeScreening
     *
     * @return string
     */
    public function getCommitteeScreening()
    {
        return $this->committee_screening;
    }

    /**
     * Set opinionRequired
     *
     * @param integer $opinionRequired
     *
     * @return Protocol
     */
    public function setOpinionRequired($opinionRequired)
    {
        $this->opinion_required = $opinionRequired;

        return $this;
    }

    /**
     * Get opinionRequired
     *
     * @return integer
     */
    public function getOpinionRequired()
    {
        return $this->opinion_required;
    }

    /**
     * Add revision
     *
     * @param \Proethos2\ModelBundle\Entity\ProtocolRevision $revision
     *
     * @return Protocol
     */
    public function addRevision(\Proethos2\ModelBundle\Entity\ProtocolRevision $revision)
    {
        $this->revision[] = $revision;

        return $this;
    }

    /**
     * Remove revision
     *
     * @param \Proethos2\ModelBundle\Entity\ProtocolRevision $revision
     */
    public function removeRevision(\Proethos2\ModelBundle\Entity\ProtocolRevision $revision)
    {
        $this->revision->removeElement($revision);
    }

    /**
     * Get revision
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRevision()
    {
        return $this->revision;
    }

    public function getRevisionUsers() {

        $users = array();
        foreach($this->getRevision() as $revision) {
            $users[] = $revision->getMember();
        }

        return $users;
    }

    /**
     * Set updatedIn
     *
     * @param \DateTime $updatedIn
     *
     * @return Protocol
     */
    public function setUpdatedIn($updatedIn)
    {
        $this->updated_in = $updatedIn;

        return $this;
    }

    /**
     * Get updatedIn
     *
     * @return \DateTime
     */
    public function getUpdatedIn()
    {
        return $this->updated_in;
    }

    /**
     * Set meeting
     *
     * @param \Proethos2\ModelBundle\Entity\Meeting $meeting
     *
     * @return Protocol
     */
    public function setMeeting(\Proethos2\ModelBundle\Entity\Meeting $meeting = null)
    {
        $this->meeting = $meeting;

        return $this;
    }

    /**
     * Get meeting
     *
     * @return \Proethos2\ModelBundle\Entity\Meeting
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * Set decisionIn
     *
     * @param \DateTime $decisionIn
     *
     * @return Protocol
     */
    public function setDecisionIn($decisionIn)
    {
        $this->decision_in = $decisionIn;

        return $this;
    }

    /**
     * Get decisionIn
     *
     * @return \DateTime
     */
    public function getDecisionIn()
    {
        return $this->decision_in;
    }

    /**
     * Set revisedIn
     *
     * @param \DateTime $revisedIn
     *
     * @return Protocol
     */
    public function setRevisedIn($revisedIn)
    {
        $this->revised_in = $revisedIn;

        return $this;
    }

    /**
     * Get revisedIn
     *
     * @return \DateTime
     */
    public function getRevisedIn()
    {
        return $this->revised_in;
    }

    /**
     * Set monitoringAction
     *
     * @param \Proethos2\ModelBundle\Entity\MonitoringAction $monitoringAction
     *
     * @return Protocol
     */
    public function setMonitoringAction(\Proethos2\ModelBundle\Entity\MonitoringAction $monitoringAction = null)
    {
        $this->monitoring_action = $monitoringAction;

        return $this;
    }

    /**
     * Get monitoringAction
     *
     * @return \Proethos2\ModelBundle\Entity\MonitoringAction
     */
    public function getMonitoringAction()
    {
        return $this->monitoring_action;
    }

    /**
     * Set monitoringActionNextDate
     *
     * @param \DateTime $monitoringActionNextDate
     *
     * @return Protocol
     */
    public function setMonitoringActionNextDate($monitoringActionNextDate)
    {
        $this->monitoring_action_next_date = $monitoringActionNextDate;

        return $this;
    }

    /**
     * Get monitoringActionNextDate
     *
     * @return \DateTime
     */
    public function getMonitoringActionNextDate()
    {
        return $this->monitoring_action_next_date;
    }

    /**
     * Set migratedId
     *
     * @param integer $migratedId
     *
     * @return Protocol
     */
    public function setMigratedId($migratedId)
    {
        $this->migrated_id = $migratedId;

        return $this;
    }

    /**
     * Get migratedId
     *
     * @return integer
     */
    public function getMigratedId()
    {
        return $this->migrated_id;
    }

    /**
     * isMigrated?
     *
     * @return boolean
     */
    public function getIsMigrated()
    {
        if(!empty($this->getMigratedId())) {
            return true;
        }
        return false;
    }

    /**
     * list XML availables
     *
     * @return array
     */
    public function getXMLAvailable()
    {
        $xml_available_list = array();

        // listing current submission and translations related
        $submissions = array($this->getMainSubmission());
        foreach($this->getMainSubmission()->getTranslations() as $translation) {
            $submissions[] = $translation;
        }

        foreach($submissions as $submission) {
            if($submission->getProtocol()->getDateInformed() and $submission->getRecruitmentStatus() and $submission->getGender()) {
                $xml_available_list[] = $submission->getLanguage();
            }
        }

        return $xml_available_list;
    }
}
