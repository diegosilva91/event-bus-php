<?php

declare(strict_types=1);

namespace EventHubCraft\Event\Domain\Bus\Event;

interface DomainEventPublisher
{
    public function publish(Event ...$events): void;

    public function release(): array;
}
