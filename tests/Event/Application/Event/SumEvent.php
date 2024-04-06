<?php

declare(strict_types=1);

namespace EventHubCraft\Tests\Event\Application\Event;

use EventHubCraft\Event\Domain\Bus\Event\Event;

final class SumEvent extends Event
{
    private array $numbers;

    public function __construct(int ...$numbers)
    {
        parent::__construct();

        $this->numbers = $numbers;
    }

    public function numbers(): array
    {
        return $this->numbers;
    }
}
