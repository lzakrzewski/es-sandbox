<?php

namespace EsSandbox\Common\Infrastructure\CommandBus;

use Psr\Log\LoggerInterface;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class LogExceptionMiddleware implements MessageBusMiddleware
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
    public function handle($event, callable $next)
    {
        try {
            $next($event);
        } catch (\Exception $exception) {
            $this->log($exception);

            throw $exception;
        }
    }

    private function log(\Exception $exception)
    {
        $this->logger->error($exception);
    }
}
