<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\AuthToken;

use JMS\Serializer\Annotation as Serializer;

class AuthToken
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $uid;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $authToken;

    /**
     * @var int
     * @Serializer\Type("int")
     */
    protected $createdAt;

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }
}
