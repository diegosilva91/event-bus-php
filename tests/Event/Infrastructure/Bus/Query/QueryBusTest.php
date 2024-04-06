<?php

declare(strict_types=1);

namespace EventHubCraft\Tests\Event\Infrastructure\Bus\Query;

use EventHubCraft\Event\Domain\Bus\Query\QueryNotRegisteredError;
use EventHubCraft\Event\Infrastructure\Messenger\Query\SymfonyQueryBus;
use EventHubCraft\Tests\Event\Application\Query\OnlyQuery;
use EventHubCraft\Tests\Event\Application\Query\SumQuery;
use EventHubCraft\Tests\Event\Application\Query\SumQueryHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

class QueryBusTest extends TestCase
{
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function testQueryBus()
    {
        $handler = new SumQueryHandler();

        $messageBusInterface = new MessageBus([
            new HandleMessageMiddleware(new HandlersLocator([
                SumQuery::class => [$handler],
            ])),
        ]);
        $queryBus = new SymfonyQueryBus($messageBusInterface);

        $responseSum = $queryBus->ask(new SumQuery(...[1, 2, 3]));

        $this->assertEquals($responseSum, 6);
    }

    public function testQueryBusNoHandler()
    {
        $this->expectException(QueryNotRegisteredError::class);
        $messageBusInterface = new MessageBus([
            new HandleMessageMiddleware(new HandlersLocator([
                SumQuery::class => [new SumQueryHandler()],
            ])),
        ]);

        $queryBus = new SymfonyQueryBus($messageBusInterface);
        $queryBus->ask(new OnlyQuery());
    }
}
