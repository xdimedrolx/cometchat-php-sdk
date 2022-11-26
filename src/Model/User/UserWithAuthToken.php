<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\User;

use JMS\Serializer\Annotation as Serializer;

class UserWithAuthToken extends User
{
    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $authToken;

    /**
     * @return string|null
     */
    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }
}
