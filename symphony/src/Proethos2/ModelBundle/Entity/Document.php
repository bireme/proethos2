<?php

namespace Proethos2\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use Cocur\Slugify\Slugify;

/**
 * Document
 *
 * @ORM\Table(name="document")
 * @ORM\Entity
 */
class Document extends Base
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
     * @ORM\Column(type="boolean")
     */
    private $status = true;

    /** 
     * @ORM\ManyToOne(targetEntity="Role") 
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=true, onDelete="SET NULL") 
     */ 
    private $role;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=1023)
     */
    private $filepath;

    public function __toString() {
        return $this->getTitle();
    }

    public function getUploadDirectory() {
        
        $upload_directory = __DIR__.'/../../../../uploads/documents';
        
        if(!is_dir($upload_directory)) {
            mkdir($upload_directory);
        }

        return $upload_directory;
    }

    public function setFile($file) {
        
        $slugify = new Slugify();
        $upload_directory = $this->getUploadDirectory();

        $filename_without_extension = str_replace("." . $file->getClientOriginalExtension(), "", $file->getClientOriginalName());
        $filename = $slugify->slugify($filename_without_extension) . "." . $file->getClientOriginalExtension();
        $filepath = $upload_directory . "/" . $filename;
        $file = $file->move($upload_directory, $filename);

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
     * Set status
     *
     * @param boolean $status
     *
     * @return Document
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

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Document
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Document
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Document
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
     * @return Document
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
     * Set role
     *
     * @param \Proethos2\ModelBundle\Entity\Role $role
     *
     * @return Document
     */
    public function setRole(\Proethos2\ModelBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \Proethos2\ModelBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }
}
