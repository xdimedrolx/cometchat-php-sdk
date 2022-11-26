<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\User;

use JMS\Serializer\Annotation as Serializer;

class UserResponse
{
    /**
     * @var User
     * @Serializer\Type("CometChat\Chat\Model\User\User")
     */
    protected $data;

    /**
     * @return User
     */
    public function getData(): User
    {
        return $this->data;
    }
}
