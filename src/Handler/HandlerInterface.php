<?php

namespace Lazybobcat\NotificationBundle\Handler;

use Lazybobcat\NotificationBundle\Notification\Notification;
use Lazybobcat\NotificationBundle\Recipient\RecipientInterface;

interface HandlerInterface
{
    public function supports(Notification $notification, RecipientInterface $recipient): bool;

    public function send(Notification $notification, RecipientInterface $recipient): void;
}
