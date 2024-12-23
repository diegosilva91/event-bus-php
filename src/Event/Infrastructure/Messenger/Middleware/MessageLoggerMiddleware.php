<?php

declare(strict_types=1);

namespace EventHubCraft\Event\Infrastructure\Messenger\Middleware;

use EventHubCraft\Event\Domain\Bus\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class MessageLoggerMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Envelope $envelope
     * @param StackInterface $stack
     *
     * @return Envelope
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $request = $envelope->getMessage();

        if ($request instanceof Request) {
            $this->logger->debug(
                'New message dispatched',
                [
                    'type' => $request->requestType(),
                    'name' => $this->nameOf($request),
                ]
            );
        }

        return $stack->next()->handle($envelope, $stack);
    }

    private function nameOf(Request $request): string
    {
        return get_class($request);
    }
}
