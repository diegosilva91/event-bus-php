<?php

declare(strict_types=1);

namespace EventHubCraft\Tests\Event\Infrastructure\Bus\Event;

use EventHubCraft\Event\Domain\Bus\Event\EventNotRegisteredError;
use EventHubCraft\Event\Infrastructure\Messenger\Event\SymfonyDomainEventPublisher;
use EventHubCraft\Event\Infrastructure\Messenger\Event\SymfonyEventBus;
use EventHubCraft\Tests\Event\Application\Event\OnlyEvent;
use EventHubCraft\Tests\Event\Application\Event\SumEvent;
use EventHubCraft\Tests\Event\Application\Event\SumEventHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

class EventBusTest extends TestCase
{
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function testEventBus()
    {
        $handler = new SumEventHandler();

        $messageBusInterface = new MessageBus([
            new HandleMessageMiddleware(new HandlersLocator([
                SumEvent::class => [$handler],
            ])),
        ]);
        $EventBus = new SymfonyEventBus($messageBusInterface);

        $responseSum = $EventBus->notify(new SumEvent(...[1, 2, 3]));

        $this->assertEquals($responseSum, null);
    }

    public function testEventBusNoHandler()
    {
        $this->expectException(EventNotRegisteredError::class);

        $messageBusInterface = new MessageBus([
            new HandleMessageMiddleware(new HandlersLocator([
                SumEvent::class => [new SumEventHandler()],
            ])),
        ]);

        $eventBus = new SymfonyEventBus($messageBusInterface);
        $eventBus->notify(new OnlyEvent());
    }

    public function testDomainEventPublisher()
    {
        $publisher = new SymfonyDomainEventPublisher();
        $publisher->publish(new OnlyEvent());
        $publisher->publish(new SumEvent());

        $this->assertEquals(2, count($publisher->release()));
    }
}
