<?php

namespace Lazybobcat\NotificationBundle\DependencyInjection;

use Lazybobcat\NotificationBundle\Handler\HandlerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class LazybobcatNotificationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(HandlerInterface::class)->addTag('notification.handler');

        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('Lazybobcat\NotificationBundle\NotifierInterface');
        $definition->replaceArgument(0, $config['handler_strategy'] ?? []);
    }
}
