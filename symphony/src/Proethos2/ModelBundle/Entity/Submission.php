<?php

namespace Proethos2\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Submission
 *
 * @ORM\Table(name="submission")
 * @ORM\Entity(repositoryClass="Proethos2\ModelBundle\Repository\SubmissionRepository")
 */
class Submission extends Base
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
     * @var string
     *
     * @ORM\Column(name="public_title", type="string", length=255)
     */
    private $publicTitle;

    /** 
     * @var Protocol
     * 
     * @ORM\ManyToOne(targetEntity="Protocol", inversedBy="submissions") 
     * @ORM\JoinColumn(name="protocol_id", referencedColumnName="id", nullable=false) 
     * @Assert\NotBlank 
     */ 
    private $author;

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
     * Set publicTitle
     *
     * @param string $publicTitle
     *
     * @return Submission
     */
    public function setPublicTitle($publicTitle)
    {
        $this->publicTitle = $publicTitle;

        return $this;
    }

    /**
     * Get publicTitle
     *
     * @return string
     */
    public function getPublicTitle()
    {
        return $this->publicTitle;
    }

    /**
     * Set author
     *
     * @param \Proethos2\ModelBundle\Entity\Protocol $author
     *
     * @return Submission
     */
    public function setAuthor(\Proethos2\ModelBundle\Entity\Protocol $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Proethos2\ModelBundle\Entity\Protocol
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
