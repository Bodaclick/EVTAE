<?php

namespace EVT\EAEBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('evteae');
        $rootNode->children()
            ->arrayNode('aws')
            ->children()
                ->scalarNode('key')->end()
                ->scalarNode('secret')->end()
                ->scalarNode('region')->end()
            ->end()
            ->end()
            ->arrayNode('api_keys')->isRequired()->requiresAtLeastOneElement()->useAttributeAsKey('key')
                ->prototype('array')
                    ->children()
                        ->scalarNode('name')->isRequired()->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
