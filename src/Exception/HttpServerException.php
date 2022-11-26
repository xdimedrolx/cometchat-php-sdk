<?php

declare(strict_types=1);

namespace CometChat\Chat\Exception;

use CometChat\Chat\Exception;
use Psr\Http\Message\ResponseInterface;

final class HttpServerException extends \RuntimeException implements Exception
{
    private $responseMessage;

    public function __construct($message = '', $code = 0, \Throwable $previous = null, ?string $responseMessage = null)
    {
        parent::__construct($message, $code, $previous);
        $this->responseMessage = $responseMessage;
    }

    public static function serverError(ResponseInterface $response, int $httpStatus = 500)
    {
        $body = $response->getBody()->__toString();
        $responseBody = [];
        if (0 <= strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            $responseBody = json_decode($body, true);
        }

        if (isset($responseBody['message'])) {
            return new self(
                sprintf('An unexpected error occurred at MIS\'s servers. Error: %s', $responseBody['message']),
                $httpStatus,
                null,
                $responseBody['message']
            );
        }

        return new self(
            'An unexpected error occurred at MIS\'s servers. Try again later and contact support if the error still exists.',
            $httpStatus
        );
    }

    public static function networkError(\Throwable $previous)
    {
        return new self('CometChat\'s servers are currently unreachable.', 0, $previous);
    }

    public static function unknownHttpResponseCode(int $code)
    {
        return new self(sprintf('Unknown HTTP response code ("%d") received from the API server', $code));
    }

    public function getResponseMessage(): ?string
    {
        return $this->responseMessage;
    }
}
