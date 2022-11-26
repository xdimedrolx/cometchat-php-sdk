<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\User;

use JMS\Serializer\Annotation as Serializer;

class CreateResponse
{
    /**
     * @var UserWithAuthToken
     * @Serializer\Type("CometChat\Chat\Model\User\UserWithAuthToken")
     */
    protected $data;

    /**
     * @return UserWithAuthToken
     */
    public function getData(): UserWithAuthToken
    {
        return $this->data;
    }
}
