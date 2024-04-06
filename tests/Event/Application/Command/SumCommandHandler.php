<?php

declare(strict_types=1);

namespace EventHubCraft\Tests\Event\Application\Command;

use EventHubCraft\Event\Domain\Bus\Command\CommandHandler;

final class SumCommandHandler implements CommandHandler
{
    public function __invoke(SumCommand $command): void
    {
        $nothing = array_sum($command->numbers());
    }
}
