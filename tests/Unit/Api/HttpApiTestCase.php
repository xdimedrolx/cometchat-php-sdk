<?php

declare(strict_types=1);

namespace CometChat\Chat\Tests\Unit\Api;

use CometChat\Chat\HttpClient\RequestBuilder;
use CometChat\Chat\Hydrator\JmsModelHydrator;
use CometChat\Chat\Tests\Unit\CometChatTestCase;
use Http\Mock\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HttpApiTestCase extends CometChatTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = new Client();
    }

    public function getMockClient(): Client
    {
        return $this->client;
    }

    public function createResponse(StreamInterface $body, int $statusCode = 200, array $headers = []): ResponseInterface
    {
        $response = $this->psr17Factory()
            ->createResponse($statusCode)
            ->withBody($body)
            ->withHeader('Content-Type', 'application/json')
        ;

        foreach ($headers as $name => $value) {
            $response->withHeader($name, $value);
        }

        return $response;
    }

    /**
     * @template T of \CometChat\Chat\Api\HttpApi
     * @param  class-string<T> $apiClass
     * @return T
     */
    public function buildApiWithMockClient(string $apiClass)
    {
        $serializer = $this->buildSerializer();

        return new $apiClass(
            $this->client,
            new RequestBuilder(),
            new JmsModelHydrator($serializer),
            $serializer
        );
    }
}
