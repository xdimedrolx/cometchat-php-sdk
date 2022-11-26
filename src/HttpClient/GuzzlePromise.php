<?php

declare(strict_types=1);

namespace CometChat\Chat\HttpClient;

use GuzzleHttp\Promise\PromiseInterface;
use Http\Promise\Promise as HttpPromise;
use Psr\Http\Message\ResponseInterface;

final class GuzzlePromise implements HttpPromise
{
    /**
     * @var PromiseInterface
     */
    private $promise;

    /**
     * @var string State of the promise
     */
    private $state;

    /**
     * @var ResponseInterface
     */
    private $response;

    private $exception;

    public function __construct(PromiseInterface $promise)
    {
        $this->state = self::PENDING;
        $this->promise = $promise->then(function ($response) {
            $this->response = $response;
            $this->state = self::FULFILLED;

            return $response;
        }, function ($reason) {
            $this->state = self::REJECTED;
            $this->exception = $reason;
            throw $reason;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null)
    {
        return new static($this->promise->then($onFulfilled, $onRejected));
    }

    /**
     * {@inheritdoc}
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * {@inheritdoc}
     */
    public function wait($unwrap = true)
    {
        $this->promise->wait(false);

        if ($unwrap) {
            if (self::REJECTED == $this->getState()) {
                throw $this->exception;
            }

            return $this->response;
        }
    }
}
