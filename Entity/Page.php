<?php
/**
 * Created by PhpStorm.
 * User: Marko Kunic
 * Date: 8/4/17
 * Time: 15:21
 */

namespace KunicMarko\SimpleCmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Customer
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @UniqueEntity(fields="title", message="Title is already taken.")
 * @UniqueEntity(fields="url", message="Url is already taken.")
 * @ORM\Table(name="simple_cms_page")
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $template;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     *
     * @ORM\ManyToOne(targetEntity="SimplePageBuilderBundle\Entity\PageBuilder", cascade={"persist"})
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="simple_page_builder", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $pageBuilder;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
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

    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }
}
