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
 * SubmissionUpload
 *
 * @ORM\Table(name="submission_upload")
 * @ORM\Entity
 */
class SubmissionUpload extends Base
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Assert\NotBlank
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Submission")
     * @ORM\JoinColumn(name="submission_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * @Assert\NotBlank
     */
    private $submission;

    /**
     * @ORM\ManyToOne(targetEntity="UploadType")
     * @ORM\JoinColumn(name="upload_type_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Assert\NotBlank
     */
    private $upload_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=1023)
     */
    private $filepath;

    /**
     * @ORM\Column(type="integer")
     */
    private $submission_number;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_monitoring_action = false;

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
     * Set filename
     *
     * @param string $filename
     *
     * @return SubmissionUpload
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
     * @return SubmissionUpload
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

    /**
     * Set submission
     *
     * @param \Proethos2\ModelBundle\Entity\Submission $submission
     *
     * @return SubmissionUpload
     */
    public function setSubmission(\Proethos2\ModelBundle\Entity\Submission $submission = null)
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
     * Set uploadType
     *
     * @param \Proethos2\ModelBundle\Entity\UploadType $uploadType
     *
     * @return SubmissionUpload
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

    public function getSubmissionDirectory() {

        $upload_directory = __DIR__.'/../../../../uploads';
        $submission_upload_directory = $upload_directory . "/" . str_pad($this->getSubmission()->getId(), 5, '0', STR_PAD_LEFT);

        if(!is_dir($submission_upload_directory)) {
            mkdir($submission_upload_directory);
        }

        return $submission_upload_directory;
    }

    public function setFile($file) {

        $slugify = new Slugify();
        $submission_upload_directory = $this->getSubmissionDirectory();

        $filename_without_extension = str_replace("." . $file->getClientOriginalExtension(), "", $file->getClientOriginalName());
        $filename = $slugify->slugify($filename_without_extension) . "." . $file->getClientOriginalExtension();
        $filepath = $submission_upload_directory . "/" . $filename;
        $file = $file->move($submission_upload_directory, $filename);

        $this->setFilename($filename);
        $this->setFilepath($filepath);

        return $this;
    }

    public function setSimpleFile($filepath, $need_real_copy = true) {

        $slugify = new Slugify();
        $submission_upload_directory = $this->getSubmissionDirectory();

        $pathinfo = pathinfo($filepath);
        $new_filepath = $submission_upload_directory . "/" . $pathinfo['basename'];

        if($need_real_copy) {
            $file = copy($filepath, $new_filepath);
        }

        $this->setFilename($pathinfo['basename']);
        $this->setFilepath($new_filepath);

        return $this;

    }

    public function setMigratedFile($filepath) {
        return $this->setSimpleFile($filepath, false);
    }

    public function getUri() {

        return "/uploads/" . str_pad($this->getSubmission()->getId(), 5, '0', STR_PAD_LEFT) . "/" . $this->getFilename();
    }

    /**
     * Set submissionNumber
     *
     * @param integer $submissionNumber
     *
     * @return SubmissionUpload
     */
    public function setSubmissionNumber($submissionNumber)
    {
        $this->submission_number = $submissionNumber;

        return $this;
    }

    /**
     * Get submissionNumber
     *
     * @return integer
     */
    public function getSubmissionNumber()
    {
        return $this->submission_number;
    }

    /**
     * Set user
     *
     * @param \Proethos2\ModelBundle\Entity\User $user
     *
     * @return SubmissionUpload
     */
    public function setUser(\Proethos2\ModelBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Proethos2\ModelBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set isMonitoringAction
     *
     * @param boolean $isMonitoringAction
     *
     * @return SubmissionUpload
     */
    public function setIsMonitoringAction($isMonitoringAction)
    {
        $this->is_monitoring_action = $isMonitoringAction;

        return $this;
    }

    /**
     * Get isMonitoringAction
     *
     * @return boolean
     */
    public function getIsMonitoringAction()
    {
        return $this->is_monitoring_action;
    }
}
