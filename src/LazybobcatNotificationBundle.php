<?php

namespace Lazybobcat\NotificationBundle;

use Lazybobcat\NotificationBundle\DependencyInjection\HandlerCompilerPass;
use Lazybobcat\NotificationBundle\DependencyInjection\LazybobcatNotificationExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LazybobcatNotificationBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new LazybobcatNotificationExtension();
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new HandlerCompilerPass());
    }
}
