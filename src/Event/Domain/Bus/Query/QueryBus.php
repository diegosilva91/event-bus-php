<?php

declare(strict_types=1);

namespace EventHubCraft\Event\Domain\Bus\Query;

interface QueryBus
{
    public function ask(Query $query);//: ?Response;
}
