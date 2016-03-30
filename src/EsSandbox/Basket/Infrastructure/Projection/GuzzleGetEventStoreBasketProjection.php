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
        $this->createProjection($basketId);

        $response = $this->client->request('GET', sprintf('%s/projection/%s/result', $this->uri, $this->projectionName($basketId)), [
            'headers' => ['Accept' => ['application/json']],
        ]);

        return json_decode($response->getBody()->getContents())->count;
    }

    private function projectionName(UuidInterface $id)
    {
        return sprintf('%s_%s', 'BasketId', $id);
    }

    private function createProjection(UuidInterface $basketId)
    {
        $this->client->request('POST', sprintf('%s/projections/onetime?name=%s&enabled=yes', $this->uri, $this->projectionName($basketId)), [
            'headers' => ['Content-Type' => ['application/json']],
            'body'    => $this->projectionFunc($basketId),
            'auth'    => ['admin', 'changeit'],
        ]);
    }

    private function projectionFunc(UuidInterface $basketId)
    {
        $name = $this->projectionName($basketId);

        return <<<STR
fromStream('$name').
    when({
       \$init : function(s,e) {return {count : 0}},
       \$any  : function(s,e) {return {count : s.count +1}}
    })
;
STR;
    }
}
