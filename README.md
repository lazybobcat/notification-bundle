# NotificationBundle

## Installation

TODO


## Configuration

The bundle configuration is optional, it allows you to define the handler to use given
the importance of the Notification.

The handler to use for a Notification can also be defined in the `Notification::getHandlers()`
method directly. Also, this method will override the bundle configuration.

```yaml
# config/packages/lazybobcat_notification.yaml
lazybobcat_notification:
    handler_strategy:
        # Handlers (by aliases) to use given the importance of the notification
        high: ['email']
        medium: ['doctrine']
        low: []
```


## Handlers

A handler is responsible for sending/writing notifications. A handler can save a notification
in any kind database, send it by email, put it in the session or even nothing! It's up to
your application.

By default, this bundle does not come with any useful notification handlers (yet?).

You will need to define your own handlers and to do that, you need to create a class that
implements the interface `Lazybobcat\NotificationBundle\Handler\HandlerInterface` and
tag it as `notification.handler` in your `config/services.yaml`:

```yaml
App\Notification\DoctrineHandler:
    tags:
        - {name: 'notification.handler', alias: 'doctrine'}
```

The `alias` is mandatory as it will be used in `handler_strategy:` configurations as well as in
`Notification::getHandlers()`!

You can create as many handlers as you want, and a Notification can be sent through any number
of handlers.

Handlers have to declare what type of Notification and what type of Recipient they can manage. If a Handler is
provided with a Notification and Recipient that does not qualify, an error will be thrown.

Example:

```php
class DoctrineHandler implements HandlerInterface
{
    // ...

    public function supports(Notification $notification, RecipientInterface $recipient): bool
    {
        return $notification instanceof DoctrineNotification && ($recipient instanceof UserRecipient || $recipient instanceof GroupRecipient);
    }

    public function send(Notification $notification, RecipientInterface $recipient): void
    {
        // ...
    }
}
```

When you send the Notification, you can either give it the Handlers (aliases) you want to send the notification
through or rely on the configuration by importance (see "Configuration"):

```php
$notification->setHandlers(['email', 'doctrine']);
$notifier->send($notification, new MyCustomRecipient());

// or

$notification->setImportance(Notification::HIGH); // rely on 'handler_strategy' configuration for 'high' importance
$notifier->send($notification, new MyCustomRecipient());
```


## Recipient

That's the notified person(s). It can be anyone or anything you want as long as you implement 
`Lazybobcat\NotificationBundle\Recipient\RecipientInterface`.

The recipient will be provided in the Handler methods and from there you will be able to extract the data you
need to send the Notification to the right place.

Here is an example of a database User recipient:

```php
class UserRecipient implements RecipientInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
```

Or an example of a group of several users identified by a "role":

```php
class GroupRecipient implements RecipientInterface
{
    private string $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
```


## Sender

The sender is optional. You can provide the notification with a Sender. A sender class implements
`Lazybobcat\NotificationBundle\Sender\SenderInterface`.

```php
$notification = new MyCustomNotification("Hello world!");
$notification->setSender(new MyCustomSender());
```
