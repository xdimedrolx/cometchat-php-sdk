<?php

declare(strict_types=1);

namespace CometChat\Chat\Hydrator;

use CometChat\Chat\Exception\HydrationException;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Serializer;
use Psr\Http\Message\ResponseInterface;

/**
 * Serialize an HTTP response to domain object.
 */
final class JmsModelHydrator implements Hydrator
{
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return ResponseInterface
     *
     * @throws HydrationException
     */
    public function hydrate(ResponseInterface $response, string $class, array $groups = ['Default'])
    {
        $body = $response->getBody()->__toString();
        $contentType = $response->getHeaderLine('Content-Type');

        // FIXME
        if (empty($body)) {
            return new $class();
        }

        if (0 !== strpos($contentType, 'application/json') && 0 !== strpos($contentType, 'application/octet-stream')) {
            throw new HydrationException('The JmsModelHydrator cannot hydrate response with Content-Type: '.$contentType);
        }

        try {
            return $this->serializer->deserialize(
                $body,
                $class,
                'json',
                DeserializationContext::create()->setGroups($groups)
            );
        } catch (\Throwable $e) {
            throw new HydrationException(sprintf('Error (%s) when trying to decode response', $e->getMessage()), 0, $e);
        }
    }
}
