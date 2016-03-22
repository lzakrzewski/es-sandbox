<?php

namespace EsSandbox\Common\Infrastructure\CommandBus;

use Assert\Assertion;
use EsSandbox\Common\Model\Event;
use Psr\Log\LoggerInterface;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class LogEventMiddleware implements MessageBusMiddleware
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        Assertion::isInstanceOf($message, Event::class);

        $this->logger->info('Event recorded', ['event' => serialize($message)]);

        $next($message);
    }
}
