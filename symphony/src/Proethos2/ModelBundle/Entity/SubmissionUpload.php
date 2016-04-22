<?php

namespace Proethos2\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Cocur\Slugify\Slugify;

/**
 * Language
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
     * @ORM\ManyToOne(targetEntity="Submission") 
     * @ORM\JoinColumn(name="submission_id", referencedColumnName="id", nullable=true) 
     * @Assert\NotBlank 
     */ 
    private $submission;

    /** 
     * @ORM\ManyToOne(targetEntity="UploadType") 
     * @ORM\JoinColumn(name="upload_type_id", referencedColumnName="id", nullable=true) 
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

    public function setFile($file) {
        
        $slugify = new Slugify();
        // echo $slugify->slugify('Hello World!'); // hello-world

        $upload_directory = __DIR__.'/../../../../uploads';
        $submission_upload_directory = $upload_directory . "/" . str_pad($this->getSubmission()->getId(), 5, '0', STR_PAD_LEFT);

        if(!is_dir($submission_upload_directory)) {
            mkdir($submission_upload_directory);
        }

        $filename_without_extension = str_replace("." . $file->getClientOriginalExtension(), "", $file->getClientOriginalName());
        $filename = $slugify->slugify($filename_without_extension) . "." . $file->getClientOriginalExtension();
        $filepath = $submission_upload_directory . "/" . $filename;
        $file = $file->move($submission_upload_directory, $filename);

        $this->setFilename($filename);
        $this->setFilepath($filepath);

        return $this;
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
}
