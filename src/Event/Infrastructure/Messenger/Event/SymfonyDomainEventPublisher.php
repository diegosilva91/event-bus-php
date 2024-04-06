<?php

declare(strict_types=1);

namespace EventHubCraft\Event\Infrastructure\Messenger\Event;

use EventHubCraft\Event\Domain\Bus\Event\DomainEventPublisher;
use EventHubCraft\Event\Domain\Bus\Event\Event;

class SymfonyDomainEventPublisher implements DomainEventPublisher
{
    private array $events = [];

    public function publish(Event ...$events): void
    {
        $this->events = array_merge($this->events, $events);
    }

    public function release(): array
    {
        $events       = $this->events;
        $this->events = [];

        return $events;
    }
}
