<?php

namespace EsSandbox\Common\Infrastructure\EventStore;

use EsSandbox\Common\Model\Event;
use EsSandbox\Common\Model\EventStore;
use EsSandbox\Common\Model\Identifier;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Ramsey\Uuid\Uuid;

class GuzzleGetEventStore implements EventStore
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
    public function commit(Event $event)
    {
        $this->writeStream($this->streamUri($event->id()), $this->serialize($event));
    }

    private function writeStream($streamUri, $content)
    {
        $this->client->request('POST', $streamUri, [
            'headers' => ['Content-Type' => ['application/vnd.eventstore.events+json']],
            'body'    => $content,
        ]);
    }

    private function streamUri(Identifier $id)
    {
        $shortName = (new \ReflectionClass($id))->getShortName();

        return sprintf('%s/streams/%s_%s', $this->uri, $shortName, $id);
    }

    private function serialize(Event $event)
    {
        return json_encode(
            [
                [
                    'eventId'   => Uuid::uuid4()->toString(),
                    'eventType' => get_class($event),
                    'data'      => (string) $event,
                ],
            ]
        );
    }

    /** {@inheritdoc} */
    public function aggregateHistoryFor(Identifier $id)
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
        $response = $this->client->request('GET', $eventUri, [
            'headers' => ['Accept' => ['application/json']],
        ]);

        return $response->getBody()->getContents();
    }

    private function unserialize($class, $contents)
    {
        return $class::fromString($this->clear($contents));
    }

    private function clear($contents)
    {
        if (is_string(json_decode($contents))) {
            return json_decode($contents);
        }

        return $contents;
    }
}
