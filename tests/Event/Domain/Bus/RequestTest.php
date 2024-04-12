<?php

namespace EventHubCraft\Tests\Event\Domain\Bus;

use EventHubCraft\Event\Domain\Bus\Request;
use EventHubCraft\Event\Domain\Bus\Uuid;
use EventHubCraft\Tests\Event\Application\Command\OnlyCommand;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testRequestId(): void
    {
        // Crear una instancia de Request
        $request = new OnlyCommand(); // Reemplaza YourRequestClass con el nombre de tu clase de solicitud

        // Obtener el ID de solicitud
        $requestId = $request->requestId();

        // Verificar si el ID de solicitud es una instancia de Uuid
        $this->assertInstanceOf(Uuid::class, $requestId);
    }
}
