<?php

namespace Lazybobcat\NotificationBundle;

use Lazybobcat\NotificationBundle\Notification\Notification;
use Lazybobcat\NotificationBundle\Handler\HandlerInterface;
use Lazybobcat\NotificationBundle\Recipient\RecipientInterface;

class Notifier implements NotifierInterface
{
    private array $handlers = [];

    private array $handlerConfiguration;

    public function __construct(array $handlerConfiguration = [])
    {
        $this->handlerConfiguration = $handlerConfiguration;
    }

    public function send(Notification $notification, RecipientInterface ...$recipients): void
    {
        foreach ($recipients as $recipient) {
            foreach ($this->getHandlers($notification, $recipient) as $handler) {
                $handler->send($notification, $recipient);
            }
        }
    }

    private function getHandlers(Notification $notification, RecipientInterface $recipient): iterable
    {
        $handlers = $notification->getHandlers();
        if (empty($handlers)) {
            if (!isset($this->handlerConfiguration[$notification->getImportance()])) {
                throw new \LogicException(sprintf("Unable to find a handler to use to send notification '%s'.", get_class($notification)));
            }

            $handlers = $this->handlerConfiguration[$notification->getImportance()];
        }

        foreach ($handlers as $handlerName) {
            /** @var HandlerInterface $handler */
            if (null === $handler = $this->getHandler($handlerName)) {
                throw new \LogicException(sprintf("The handler '%s' has not been found.", $handlerName));
            }

            if (!$handler->supports($notification, $recipient)) {
                throw new \LogicException(sprintf("The handler '%s' does not support the notification.", $handlerName));
            }

            yield $handler;
        }
    }

    private function getHandler($name)
    {
        return $this->handlers[$name] ?? null;
    }

    public function addHandler(HandlerInterface $handler, string $alias)
    {
        $this->handlers[$alias] = $handler;
    }
}
