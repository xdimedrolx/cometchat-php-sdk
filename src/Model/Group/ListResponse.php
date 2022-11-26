<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\Group;

use CometChat\Chat\Model\MetaPaginationResponse;
use JMS\Serializer\Annotation as Serializer;

class ListResponse extends MetaPaginationResponse
{
    /**
     * @var Group[]
     * @Serializer\Type("array<CometChat\Chat\Model\Group\Group>")
     */
    protected $data = [];

    /**
     * @return Group[]
     */
    public function getData(): array
    {
        return $this->data;
    }
}
