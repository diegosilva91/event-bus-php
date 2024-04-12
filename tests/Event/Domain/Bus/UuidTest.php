<?php

declare(strict_types=1);

namespace EventHubCraft\Tests\Event\Domain\Bus;

use EventHubCraft\Event\Domain\Bus\Uuid;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{
    public function testUuidCreation(): void
    {
        $uuidString = '550e8400-e29b-41d4-a716-446655440000';
        $uuid = Uuid::create($uuidString);

        $this->assertInstanceOf(Uuid::class, $uuid);
        $this->assertSame($uuidString, $uuid->value());
    }

    public function testUuidRandomGeneration(): void
    {
        $uuid = Uuid::random();

        $this->assertInstanceOf(Uuid::class, $uuid);
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
            $uuid->value()
        );
    }

    public function testInvalidUuidCreation(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^<.*> does not allow the value <.*>.$/');

        Uuid::create('invalid-uuid');
    }

    public function testUuidToString(): void
    {
        $uuidString = '550e8400-e29b-41d4-a716-446655440000';
        $uuid = Uuid::create($uuidString);

        $this->assertSame($uuidString, (string) $uuid);
    }
}
