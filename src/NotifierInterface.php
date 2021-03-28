<?php

namespace Lazybobcat\NotificationBundle;

use Lazybobcat\NotificationBundle\Handler\HandlerInterface;
use Lazybobcat\NotificationBundle\Notification\Notification;
use Lazybobcat\NotificationBundle\Recipient\RecipientInterface;

interface NotifierInterface
{
    public function send(Notification $notification, RecipientInterface ...$recipients): void;

    public function addHandler(HandlerInterface $handler, string $alias);
}
