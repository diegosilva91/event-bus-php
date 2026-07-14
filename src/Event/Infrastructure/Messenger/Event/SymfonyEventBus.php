<?php

declare(strict_types=1);

namespace EventHubCraft\Event\Infrastructure\Messenger\Event;

use EventHubCraft\Event\Domain\Bus\Event\Event;
use EventHubCraft\Event\Domain\Bus\Event\EventBus;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

final class SymfonyEventBus implements EventBus
{
    public function __construct(
        private MessageBusInterface $bus
    ) {
    }

    public function publish(Event $event): void
    {
        try {
            $this->bus->dispatch(
                (new Envelope($event))
                    ->with(new DispatchAfterCurrentBusStamp())
            );
        } catch (NoHandlerForMessageException) {
            return;
        }
    }
}
