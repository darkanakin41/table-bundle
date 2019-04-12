<?php

namespace PLejeune\TableBundle\DependencyInjection;


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
        $rootNode = $treeBuilder->root('p_lejeune_table');

        $rootNode
            ->children()
                ->arrayNode('template')
                ->children()
                    ->arrayNode('fields')
                        ->isRequired()
                        ->scalarPrototype()->end()
                    ->end()
                    ->scalarNode('table')
                        ->isRequired()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
