<?php

namespace EventHubCraft\Event\Domain\Bus\Command;

use EventHubCraft\Event\Domain\Bus\Request;

abstract class Command extends Request
{
    public function requestType(): string
    {
        return 'command';
    }
}
