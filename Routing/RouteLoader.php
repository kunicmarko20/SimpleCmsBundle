<?php

namespace KunicMarko\SimpleCmsBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

class RouteLoader extends Loader
{
    private $entityManager;

    private static $loaded = false;

    public function __construct(EntityManager $manager)
    {
        $this->entityManager = $manager;
    }

    public function load($resource, $type = null)
    {
        if (self::$loaded === true) {
            throw new \RuntimeException('Do not load tree routes twice');
        }

        $routes = new RouteCollection();

        //load routes from db
        $defaults = [
            '_controller' => 'AppBundle:Default:index',
            'id' => '13',
        ];
        $route = new Route('going/for/the/win', $defaults);
        $routes->add('cms_route_13', $route);


        self::$loaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return $type === 'simple_route';
    }
}