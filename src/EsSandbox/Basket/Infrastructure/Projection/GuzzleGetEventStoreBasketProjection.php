<?php

namespace EsSandbox\Basket\Infrastructure\Projection;

use EsSandbox\Basket\Application\Projection\BasketProjection;
use GuzzleHttp\Client;
use Ramsey\Uuid\UuidInterface;

class GuzzleGetEventStoreBasketProjection implements BasketProjection
{
    /** @var Client */
    private $client;

    /** @var string */
    private $uri;

    /**
     * @param Client $client
     * @param string $host
     * @param string $port
     */
    public function __construct(Client $client, $host, $port)
    {
        $this->client = $client;
        $this->uri    = sprintf('%s:%s', $host, $port);
    }

    /** {@inheritdoc} */
    public function get(UuidInterface $basketId)
    {
        $uri = sprintf('%s/projection/transient', $this->uri);

        $response = $this->client->request('GET', $uri, [
            'headers' => ['Accept' => ['application/json']],
        ]);

        var_dump($response->getBody()->getContents());die;
    }
}
