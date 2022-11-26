<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\Group;

use JMS\Serializer\Annotation as Serializer;

class CreateResponse
{
    /**
     * @var Group
     * @Serializer\Type("CometChat\Chat\Model\Group\Group")
     */
    protected $data;

    /**
     * @return Group
     */
    public function getData(): Group
    {
        return $this->data;
    }
}
