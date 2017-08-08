<?php

namespace KunicMarko\SimpleCmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private $rootDirectory;

    public function  __construct($rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('simple_cms');
        $rootNode
            ->children()
                ->scalarNode('template_directory')
                    ->defaultValue($this->rootDirectory . '/Resources/views')
                ->end()
            ->end();

        return $treeBuilder;
    }
}