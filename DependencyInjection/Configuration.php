<?php

namespace Neogen\BouncerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bouncer');
        $rootNode
            ->children()
            ->scalarNode('bounce_enabled')
            ->defaultTrue()
            ->cannotBeEmpty()
            ->info('enable/disable bounce')
            ->end()
            ->scalarNode('complaint_enabled')
            ->defaultFalse()
            ->cannotBeEmpty()
            ->info('enable/disable complaint')
            ->end()
            ->scalarNode('http_client')
            ->cannotBeEmpty()
            ->isRequired()
            ->info('')
            ->end()
            ->scalarNode('message_factory')
            ->cannotBeEmpty()
            ->isRequired()
            ->info('')
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
