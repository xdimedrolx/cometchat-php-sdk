<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\User;

use CometChat\Chat\Model\MetaPaginationResponse;
use JMS\Serializer\Annotation as Serializer;

class ListResponse extends MetaPaginationResponse
{
    /**
     * @var User[]
     * @Serializer\Type("array<CometChat\Chat\Model\User\User>")
     */
    protected $data = [];

    /**
     * @return User[]
     */
    public function getData(): array
    {
        return $this->data;
    }
}
