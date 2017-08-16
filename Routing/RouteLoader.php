<?php

namespace KunicMarko\SimpleCmsBundle\Routing;

use KunicMarko\SimpleCmsBundle\Entity\Page;
use Symfony\Component\Config\Loader\Loader;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use KunicMarko\SimpleCmsBundle\Controller\PageController;

class RouteLoader extends Loader
{
    /** @var \Doctrine\ORM\EntityRepository  */
    private $pageRepository;
    /** @var RouteCollection  */
    private $routes;

    private static $loaded = false;

    public function __construct(EntityManager $manager)
    {
        $this->pageRepository = $manager->getRepository(Page::class);
        $this->routes = new RouteCollection();
    }

    public function load($resource, $type = null)
    {
        if (self::$loaded === true) {
            throw new \RuntimeException('Do not load tree routes twice');
        }

        $pages = $this->pageRepository->findAll();
        $this->mapCustomPages($pages);

        self::$loaded = true;

        return $this->routes;
    }

    protected function mapCustomPages($pages)
    {
        /** @var Page $page */
        foreach ($pages as $page) {
            $defaults = [
                '_controller' => 'SimpleCmsBundle:Page:index',
                'pageId' => $page->getId(),
            ];
            $route = new Route($page->getPath(), $defaults);
            $this->routes->add('simple_cms_page_' . $page->getId(), $route);
        }
    }

    public function supports($resource, $type = null)
    {
        return $type === 'simple_cms_route';
    }
}
