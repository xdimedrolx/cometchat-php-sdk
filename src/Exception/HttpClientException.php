<?php

declare(strict_types=1);

namespace CometChat\Chat\Exception;

use CometChat\Chat\Exception;
use CometChat\Chat\Model\Error;

final class HttpClientException extends \RuntimeException implements Exception
{
    /**
     * @var Error|null
     */
    private $error;

    public function __construct(string $message, int $code, ?Error $error)
    {
        parent::__construct($message, $code);

        $this->error = $error;
    }

    public static function badRequest(?Error $error): self
    {
        return new self(
            'Bad request.',
            400,
            $error
        );
    }

    public static function unauthorized(?Error $error): self
    {
        return new self(
            'Your credentials are incorrect.',
            401,
            $error
        );
    }

    public static function notFound(?Error $error): self
    {
        return new self(
            'The endpoint you have tried to access does not exist.',
            404,
            $error
        );
    }

    public static function payloadTooLarge(?Error $error): self
    {
        return new self(
            'Payload too large, your total attachment size is too big.',
            413,
            $error
        );
    }

    public static function alreadyExists(?Error $error): self
    {
        return new self(
            'Entity already exists.',
            409,
            $error
        );
    }

    public static function forbidden(?Error $error): self
    {
        return new self(
            'Forbidden.',
            403,
            $error
        );
    }

    public function getError(): ?Error
    {
        return $this->error;
    }
}
