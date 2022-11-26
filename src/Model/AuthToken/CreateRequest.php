<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\AuthToken;

use JMS\Serializer\Annotation as Serializer;

class CreateRequest
{
    /**
     * Generates new auth token forcefully.
     *
     * @var bool
     * @Serializer\Type("bool")
     */
    protected $force = false;

    public function __construct(bool $force = false)
    {
        $this->force = $force;
    }

    /**
     * @return bool
     */
    public function isForce(): bool
    {
        return $this->force;
    }
}