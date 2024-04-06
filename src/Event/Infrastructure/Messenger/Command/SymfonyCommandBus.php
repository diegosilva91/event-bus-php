<?php

declare(strict_types=1);

namespace EventHubCraft\Event\Infrastructure\Messenger\Command;

use EventHubCraft\Event\Domain\Bus\Command\Command;
use EventHubCraft\Event\Domain\Bus\Command\CommandBus;
use EventHubCraft\Event\Domain\Bus\Command\CommandNotRegisteredError;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyCommandBus implements CommandBus
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->bus = $commandBus;
    }

    public function dispatch(Command $command): void
    {
        try {
            $this->bus->dispatch($command);
        } catch (NoHandlerForMessageException) {
            throw new CommandNotRegisteredError($command);
        }
    }
}
