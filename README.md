# Event Hub Craft

Small CQRS / DDD bus package built on top of Symfony Messenger.

Use it when you want a thin abstraction for:

- `CommandBus`
- `QueryBus`
- `EventBus`
- `DomainEventPublisher`

The package is designed to be consumed from other PHP projects, especially Symfony-based ones.

## Requirements

- PHP `>= 8.1`
- Symfony Messenger `5.3.*`

## Install

```bash
composer require bundle-bus/eventhub-craft
```

If you are working inside this repository, you can also install dependencies with Docker:

```bash
docker run --rm --interactive --tty --volume $PWD:/app composer:2 composer install
```

## What this package gives you

The package ships with three domain bus contracts and Symfony Messenger adapters:

- `EventBus` -> `SymfonyEventBus`
- `CommandBus` -> `SymfonyCommandBus`
- `QueryBus` -> `SymfonyQueryBus`

It also includes a small `DomainEventPublisher` implementation for collecting domain events inside your aggregate or application service.

Important behavior:

- Commands are dispatched and are expected to have a handler.
- Queries are dispatched and are expected to return a result.
- Events are published and may have zero, one, or many listeners.

## How to integrate it in your Symfony project

### 1. Register the services

Expose the buses as services in your Symfony application. A simple approach is to wire them directly to Messenger buses.

```yaml
# config/services.yaml
services:
  EventHubCraft\Event\Infrastructure\Messenger\Command\SymfonyCommandBus:
    arguments:
      $commandBus: '@messenger.bus.commands'

  EventHubCraft\Event\Infrastructure\Messenger\Query\SymfonyQueryBus:
    arguments:
      $queryBus: '@messenger.bus.queries'

  EventHubCraft\Event\Infrastructure\Messenger\Event\SymfonyEventBus:
    arguments:
      $bus: '@messenger.bus.events'

  EventHubCraft\Event\Infrastructure\Messenger\Event\SymfonyDomainEventPublisher: ~
```

If you prefer aliases, map the contracts to the adapters:

```yaml
services:
  EventHubCraft\Event\Domain\Bus\Command\CommandBus:
    alias: EventHubCraft\Event\Infrastructure\Messenger\Command\SymfonyCommandBus

  EventHubCraft\Event\Domain\Bus\Query\QueryBus:
    alias: EventHubCraft\Event\Infrastructure\Messenger\Query\SymfonyQueryBus

  EventHubCraft\Event\Domain\Bus\Event\EventBus:
    alias: EventHubCraft\Event\Infrastructure\Messenger\Event\SymfonyEventBus
```

### 2. Configure Messenger buses

Use separate buses so each intent stays explicit.

```yaml
# config/packages/messenger.yaml
framework:
  messenger:
    buses:
      messenger.bus.commands:
        default_middleware: allow_no_handlers
        middleware:
          - validation

      messenger.bus.queries:
        default_middleware: allow_no_handlers

      messenger.bus.events:
        default_middleware: allow_no_handlers
```

This keeps your application aligned with CQRS:

- commands mutate state
- queries read state
- events notify the rest of the system

### 3. Define your message classes

Extend the package base classes in your application layer.

```php
<?php

declare(strict_types=1);

namespace App\Application\Command;

use EventHubCraft\Event\Domain\Bus\Command\Command;

final class CreateUserCommand extends Command
{
    public function __construct(
        public readonly string $email,
        public readonly string $name,
    ) {
        parent::__construct();
    }
}
```

```php
<?php

declare(strict_types=1);

namespace App\Application\Query;

use EventHubCraft\Event\Domain\Bus\Query\Query;

final class FindUserQuery extends Query
{
    public function __construct(
        public readonly string $userId,
    ) {
        parent::__construct();
    }
}
```

```php
<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use EventHubCraft\Event\Domain\Bus\Event\Event;

final class UserRegistered extends Event
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
```

### 4. Create handlers

Handlers are regular invokable services.

```php
<?php

declare(strict_types=1);

namespace App\Application\Command;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateUserHandler
{
    public function __invoke(CreateUserCommand $command): void
    {
        // mutate state
    }
}
```

```php
<?php

declare(strict_types=1);

namespace App\Application\Query;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class FindUserHandler
{
    public function __invoke(FindUserQuery $query): array
    {
        return [
            'id' => $query->userId,
        ];
    }
}
```

```php
<?php

declare(strict_types=1);

namespace App\Application\Event;

use App\Domain\User\Event\UserRegistered;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SendWelcomeEmailHandler
{
    public function __invoke(UserRegistered $event): void
    {
        // react to the event
    }
}
```

## How to use the buses

```php
<?php

declare(strict_types=1);

use App\Application\Command\CreateUserCommand;
use App\Application\Query\FindUserQuery;
use App\Domain\User\Event\UserRegistered;
use EventHubCraft\Event\Domain\Bus\Command\CommandBus;
use EventHubCraft\Event\Domain\Bus\Event\EventBus;
use EventHubCraft\Event\Domain\Bus\Query\QueryBus;

final class UserService
{
    public function __construct(
        private CommandBus $commandBus,
        private QueryBus $queryBus,
        private EventBus $eventBus,
    ) {
    }

    public function createUser(string $email, string $name): void
    {
        $this->commandBus->dispatch(new CreateUserCommand($email, $name));
        $this->eventBus->publish(new UserRegistered([
            'email' => $email,
            'name' => $name,
        ]));
    }

    public function getUser(string $userId): array
    {
        return $this->queryBus->ask(new FindUserQuery($userId));
    }
}
```

## Domain events

Use `DomainEventPublisher` inside aggregates or application services when you want to collect events and release them later.

```php
$publisher->publish(new UserRegistered(['email' => $email]));
$events = $publisher->release();
```

This package does not force a storage strategy. You decide whether events are:

- dispatched immediately
- buffered until transaction commit
- persisted in an outbox

## CQRS guidance

To keep the package and your consuming projects clean:

- keep command handlers side-effecting
- keep query handlers read-only
- keep events immutable
- avoid putting domain logic inside the bus adapters
- avoid coupling your domain to Symfony classes

## Tests

Run the unit suite with:

```bash
docker run --rm --interactive --tty --volume $PWD:/app composer:2 composer run-unit-tests
```

## Style

Check coding style with:

```bash
docker run --rm --interactive --tty --volume $PWD:/app composer:2 composer check-style
```

Fix coding style with:

```bash
docker run --rm --interactive --tty --volume $PWD:/app composer:2 composer fix-style
```
