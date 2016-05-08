<?php

namespace EsSandbox\Common\Infrastructure\EventStore;

use EsSandbox\Common\Infrastructure\EventStore\Mapper\ShortNameToFQN;
use EsSandbox\Common\Model\Event;
use EsSandbox\Common\Model\EventStore;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

//Todo:
// - test for serialization
// - test ???? if all events was added
class GuzzleEventStore implements EventStore
{
    /** @var Client */
    private $client;

    /** @var ShortNameToFQN */
    private $mapper;

    /** @var string */
    private $uri;

    /**
     * @param Client         $client
     * @param ShortNameToFQN $mapper
     * @param string         $host
     * @param string         $port
     */
    public function __construct(Client $client, ShortNameToFQN $mapper, $host, $port)
    {
        $this->client = $client;
        $this->mapper = $mapper;
        $this->uri    = sprintf('%s:%s', $host, $port);
    }

    /** {@inheritdoc} */
    public function commit(array $events)
    {
        // Todo: Write multiple events in one api call

        foreach ($events as $event) {
            $this->writeStream($this->streamUri($event->id()), $this->serialize($event));
        }
    }

    private function writeStream($streamUri, $content)
    {
        $this->client->request('POST', $streamUri, [
            'headers' => ['Content-Type' => ['application/vnd.eventstore.events+json']],
            'body'    => $content,
        ]);
    }

    private function streamUri(UuidInterface $id)
    {
        $streamName = $this->streamName($id);

        return sprintf('%s/streams/%s', $this->uri, $streamName, $id);
    }

    private function serialize(Event $event)
    {
        $reflection = new \ReflectionClass($event);

        return json_encode(
            [
                [
                    'eventId'   => Uuid::uuid4()->toString(),
                    'eventType' => $reflection->getShortName(),
                    'data'      => $event->toArray(),
                ],
            ]
        );
    }

    /** {@inheritdoc} */
    public function aggregateHistoryFor(UuidInterface $id)
    {
        $data   = $this->readStream($this->streamUri($id));
        $events = [];

        if (empty($data) || !isset($data['entries'])) {
            return [];
        }

        foreach ($data['entries'] as $eventData) {
            $contents = $this->readEvent($eventData['id']);
            $events[] = $this->unserialize($eventData['summary'], $contents);
        }

        return $events;
    }

    private function readStream($streamUri)
    {
        try {
            $response = $this->client->request('GET', $streamUri, [
                'headers' => ['Accept' => ['application/vnd.eventstore.events+json']],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return [];
        }
    }

    private function readEvent($eventUri)
    {
        $response = $this->client
            ->request(
                'GET',
                $eventUri,
                [
                    'headers' => [
                        'Accept' => ['application/json'],
                    ],
                ]
            );

        return $response->getBody()->getContents();
    }

    private function unserialize($class, $contents)
    {
        $fqn = $this->mapper->get($class);

        return $fqn::fromArray($this->clear($contents));
    }

    private function clear($contents)
    {
        return (array) json_decode($contents, true);
    }

    private function streamName(UuidInterface $id)
    {
        return $id->toString();
    }
}
