<?php

declare(strict_types=1);

namespace CometChat\Chat;

use CometChat\Chat\Exception\InvalidArgumentException;

/**
 * We need to override Webmozart\Assert because we want to throw our own Exception.
 */
final class Assert extends \Webmozart\Assert\Assert
{
    protected static function reportInvalidArgument($message)
    {
        throw new InvalidArgumentException($message);
    }
}
