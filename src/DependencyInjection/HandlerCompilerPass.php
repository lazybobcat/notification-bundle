<?php

namespace Lazybobcat\NotificationBundle\DependencyInjection;

use Lazybobcat\NotificationBundle\NotifierInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class HandlerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(NotifierInterface::class)) {
            return;
        }

        $definition = $container->findDefinition(NotifierInterface::class);
        $taggedServices = $container->findTaggedServiceIds('notification.handler');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                if (isset($attributes['alias'])) {
                    $definition->addMethodCall('addHandler', [new Reference($id), $attributes['alias']]);
                }
            }
        }
    }
}
