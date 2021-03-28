<?php

namespace Lazybobcat\NotificationBundle\Notification;

use Lazybobcat\NotificationBundle\Sender\SenderInterface;

class Notification
{
    const HIGH = 'high';
    const MEDIUM = 'medium';
    const LOW = 'low';

    const LEVELS = [
        self::HIGH => 300,
        self::MEDIUM => 200,
        self::LOW => 100,
    ];

    private $importance = self::MEDIUM;
    private array $handlers = [];
    private string $subject;
    private string $message;
    private ?SenderInterface $sender;
    private bool $grouped = false;

    public function __construct(array $handlers = [])
    {
        $this->handlers = $handlers;
    }

    public function getImportance(): string
    {
        return $this->importance;
    }

    public function setImportance(string $importance): Notification
    {
        $this->importance = $importance;

        return $this;
    }

    public function getHandlers(): array
    {
        return $this->handlers;
    }

    public function setHandlers(array $handlers): Notification
    {
        $this->handlers = $handlers;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): Notification
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): Notification
    {
        $this->message = $message;

        return $this;
    }

    public function getSender(): ?SenderInterface
    {
        return $this->sender;
    }

    public function setSender(?SenderInterface $sender): Notification
    {
        $this->sender = $sender;

        return $this;
    }

    public function isGrouped(): bool
    {
        return $this->grouped;
    }

    public function setGrouped(bool $grouped): Notification
    {
        $this->grouped = $grouped;

        return $this;
    }
}
