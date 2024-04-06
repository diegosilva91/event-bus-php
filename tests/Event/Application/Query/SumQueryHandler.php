<?php

declare(strict_types=1);

namespace EventHubCraft\Tests\Event\Application\Query;

use EventHubCraft\Event\Domain\Bus\Query\QueryHandler;

final class SumQueryHandler implements QueryHandler
{
    public function __invoke(SumQuery $query): int
    {
        return array_sum($query->numbers());
    }
}
