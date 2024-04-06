<?php

declare(strict_types=1);

namespace EventHubCraft\Event\Domain\Bus\Command;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
