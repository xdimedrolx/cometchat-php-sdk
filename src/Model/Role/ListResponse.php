<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\Role;

use CometChat\Chat\Model\MetaPaginationResponse;
use JMS\Serializer\Annotation as Serializer;

class ListResponse extends MetaPaginationResponse
{
    /**
     * @var Role[]
     * @Serializer\Type("array<CometChat\Chat\Model\Role\Role>")
     */
    protected $data = [];

    /**
     * @return Role[]
     */
    public function getData(): array
    {
        return $this->data;
    }
}
