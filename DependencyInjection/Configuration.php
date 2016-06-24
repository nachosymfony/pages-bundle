<?php

namespace nacholibre\PagesBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('nacholibre_pages');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
                ->scalarNode('entity_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('urls')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('type')->defaultValue('slug')->end()
                        ->scalarNode('prefix')->defaultValue('info/')->end()
                    ->end()
                ->end()
                ->arrayNode('editor')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultValue('simple')->end()
                        ->scalarNode('config_name')->defaultValue('default')->end()
                        ->booleanNode('elfinder_integration')->defaultFalse()->end()
                        ->scalarNode('elfinder_browse_route')->defaultValue('elfinder')->end()
                        ->scalarNode('elfinder_instance')->defaultValue('default')->end()
                        ->scalarNode('elfinder_homefolder')->defaultValue('')->end()
                    ->end()
                ->end()
        ;

        return $treeBuilder;
    }
}
