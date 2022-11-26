<?php

declare(strict_types=1);

namespace CometChat\Chat\Model;

use JMS\Serializer\Annotation as Serializer;

abstract class MetaPaginationResponse
{
    /**
     * @var MetaPagination|null
     * @Serializer\Type("CometChat\Chat\Model\MetaPagination")
     */
    protected $meta;

    /**
     * @return MetaPagination|null
     */
    public function getMeta(): ?MetaPagination
    {
        return $this->meta;
    }
}
