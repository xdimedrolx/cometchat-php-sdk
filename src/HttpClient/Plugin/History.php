<?php

declare(strict_types=1);

namespace CometChat\Chat\HttpClient\Plugin;

use Http\Client\Common\Plugin\Journal;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A plugin to remember the last response.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class History implements Journal
{
    use HistoryTrait;

    /**
     * @var ResponseInterface
     */
    private $lastResponse;

    /**
     * @return ResponseInterface|null
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    public function addSuccess(RequestInterface $request, ResponseInterface $response)
    {
        $this->lastResponse = $response;
    }
}
