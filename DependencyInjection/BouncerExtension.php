<?php

namespace Neogen\BouncerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BouncerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('bouncer.bounce_enabled', $config['bounce_enabled']);
        $container->setParameter('bouncer.complaint_enabled', $config['complaint_enabled']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->getDefinition('neogen.request_handler')
            ->replaceArgument(1, new Reference($config['http_client']))
            ->replaceArgument(2, new Reference($config['message_factory']));
        $container->getDefinition('neogen.email_bounce_filter_listener')
            ->replaceArgument(1, $config['bounce_enabled'])
            ->replaceArgument(2, $config['complaint_enabled']);
    }
}
