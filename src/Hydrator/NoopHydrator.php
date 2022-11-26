<?php

declare(strict_types=1);

namespace CometChat\Chat\Hydrator;

use Psr\Http\Message\ResponseInterface;

/**
 * Do not serialize at all. Just return a PSR-7 response.
 */
final class NoopHydrator implements Hydrator
{
    /**
     * @throws \LogicException
     */
    public function hydrate(ResponseInterface $response, string $class, array $groups = ['Default'])
    {
        throw new \LogicException('The NoopHydrator should never be called');
    }
}
