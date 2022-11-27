<?php

declare(strict_types=1);

namespace CometChat\Chat\Model;

use JMS\Serializer\Annotation as Serializer;

class DeleteResponse
{
    /**
     * @var DataSuccess
     * @Serializer\Type("CometChat\Chat\Model\DataSuccess")
     */
    protected $data;

    /**
     * @return DataSuccess
     */
    public function getData(): DataSuccess
    {
        return $this->data;
    }
}
