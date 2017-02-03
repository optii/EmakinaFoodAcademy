<?php

namespace DamDan\AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dam_dan_app');


        $rootNode
            ->children()
                ->scalarNode('administrator_email')->end()
                ->scalarNode('administrator_name')->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
