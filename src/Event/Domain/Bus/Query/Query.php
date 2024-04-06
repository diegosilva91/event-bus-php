<?php

declare(strict_types=1);

namespace EventHubCraft\Event\Domain\Bus\Query;

use EventHubCraft\Event\Domain\Bus\Request;

class Query extends Request
{
    public function requestType(): string
    {
        return 'query';
    }
}
