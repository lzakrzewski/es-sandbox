<?php

namespace tests\EsSandbox\Common\Infrastructure\CommandBus;

use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;
use SimpleBus\Message\Subscriber\Resolver\MessageSubscribersResolver;

class CollectsMessageSubscribersMiddleware implements MessageBusMiddleware
{
    /** @var MessageSubscribersResolver */
    private $messageSubscribersResolver;

    /** @var array */
    private $messageSubscribers = [];

    /**
     * @param MessageSubscribersResolver $messageSubscribersResolver
     */
    public function __construct(MessageSubscribersResolver $messageSubscribersResolver)
    {
        $this->messageSubscribersResolver = $messageSubscribersResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        $this->collect($message);

        $next($message);
    }

    /**
     * @return array
     */
    public function messageSubscribers()
    {
        return $this->messageSubscribers;
    }

    private function collect($message)
    {
        $messageSubscribers = $this->messageSubscribersResolver->resolve($message);

        foreach ($messageSubscribers as $messageSubscriber) {
            $this->messageSubscribers[] = $messageSubscriber[0];
        }
    }
}
