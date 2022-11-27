<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\Role;

use JMS\Serializer\Annotation as Serializer;

class RoleResponse
{
    /**
     * @var Role
     * @Serializer\Type("CometChat\Chat\Model\Role\Role")
     */
    protected $data;

    /**
     * @return Role
     */
    public function getData(): Role
    {
        return $this->data;
    }
}
