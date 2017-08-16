<?php
/**
 * Created by PhpStorm.
 * User: Marko Kunic
 * Date: 8/4/17
 * Time: 15:21
 */

namespace KunicMarko\SimpleCmsBundle\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Page
 * @package KunicMarko\SimpleCmsBundle\Entity
 * @ORM\Entity()
 * @UniqueEntity(fields="title", message="Title is already taken.")
 * @UniqueEntity(fields="path", message="Url is already taken.")
 * @ORM\Table(name="simple_cms_page")
 * @ORM\HasLifecycleCallbacks()
 */

class Page
{

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Name cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Title cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Meta Description cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $template;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     *
     * @ORM\ManyToOne(targetEntity="KunicMarko\SimplePageBuilderBundle\Entity\PageBuilder", cascade={"persist"})
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="simple_page_builder", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $pageBuilder;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = '/'. ltrim($path, '/');
    }

    public function getPageBuilder()
    {
        return $this->pageBuilder;
    }

    /**
     * @param mixed $pageBuilder
     */
    public function setPageBuilder($pageBuilder)
    {
        $this->pageBuilder = $pageBuilder;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function onPersistUpdate()
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTime();
        }

        $this->updatedAt = new \DateTime();

        if ($this->path === null) {
            $slugify = new Slugify();
            $this->setPath($slugify->slugify($this->name));
        }
    }

    public function __toString()
    {
        if ($this->name !== null) {
            return $this->name;
        }
        return 'Page';
    }
}
