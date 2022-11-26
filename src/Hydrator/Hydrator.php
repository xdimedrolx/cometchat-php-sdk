<?php

declare(strict_types=1);

namespace CometChat\Chat\Hydrator;

use CometChat\Chat\Exception\HydrationException;
use Psr\Http\Message\ResponseInterface;

/**
 * Deserialize a PSR-7 response to something else.
 */
interface Hydrator
{
    /**
     * @return ResponseInterface
     *
     * @throws HydrationException
     */
    public function hydrate(ResponseInterface $response, string $class, array $groups = ['Default']);
}
