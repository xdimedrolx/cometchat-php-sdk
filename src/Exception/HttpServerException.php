<?php

declare(strict_types=1);

namespace CometChat\Chat\Exception;

use CometChat\Chat\Exception;
use CometChat\Chat\Model\Error;

final class HttpServerException extends \RuntimeException implements Exception
{
    /**
     * @var Error|null
     */
    private $error;

    public function __construct(string $message, int $httpCode, ?\Throwable $previous = null, ?Error $error = null)
    {
        parent::__construct($message, $httpCode, $previous);
        $this->error = $error;
    }

    public static function serverError(int $httpCode, ?Error $error)
    {
        return new self(
            'An unexpected error occurred at CometChat\'s servers. Try again later and contact support if the error still exists.',
            $httpCode,
            null,
            $error
        );
    }

    public static function networkError(\Throwable $previous)
    {
        return new self(
            'CometChat\'s servers are currently unreachable.',
            0,
            $previous
        );
    }

    public static function unknownHttpResponseCode(int $httpCode, ?Error $error)
    {
        return new self(
            sprintf('Unknown HTTP response code ("%d") received from the API server', $httpCode),
            $httpCode,
            null,
            $error
        );
    }

    public function getError(): ?Error
    {
        return $this->error;
    }
}
