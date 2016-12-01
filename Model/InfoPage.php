<?php

namespace nacholibre\PagesBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\MappedSuperclass
 * @ORM\Table(name="info_page", uniqueConstraints={@ORM\UniqueConstraint(name="slug", columns={"slug"})})
 * @UniqueEntity("name")
 */
class InfoPage
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", unique=true, length=255)
     */
    protected $name;

    /**
    * @ORM\Column(name="static", type="boolean")
    */
    protected $static = false;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="slug", type="string", length=255)
     */
    protected $slug;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    protected $content;

    /**
    * @ORM\Column(name="desc2", type="text")
    */
    protected $desc2;

    /**
     * @var \DateTime
     * @ORM\Column(name="created", type="datetime")
     */
    protected $dateCreated;

    /**
     * @var \DateTime
     * @ORM\Column(name="modified", type="datetime")
     */
    protected $dateModified;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

    function __construct() {
        if (!$this->getDateCreated()) {
            $this->setDateCreated(new \Datetime());
        }
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return InfoPage
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return InfoPage
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return InfoPage
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set editable
     *
     * @param boolean $editable
     * @return InfoPage
     */
    public function setStatic($static)
    {
        $this->static = $static;

        return $this;
    }

    /**
     * Get editable
     *
     * @return boolean 
     */
    public function getStatic()
    {
        return $this->static;
    }

    /**
     * Get dateModified
     *
     * @return \DateTime 
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return InfoPage
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Set dateModified
     *
     * @param \DateTime $dateModified
     *
     * @return InfoPage
     */
    public function setDateModified($dateModified)
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
