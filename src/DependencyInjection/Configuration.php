<?php

namespace Darkanakin41\TableBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('darkanakin41_table');

        $rootNode
            ->children()
                ->arrayNode('template')
                ->children()
                    ->scalarNode('fields')->end()
                    ->scalarNode('table')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
