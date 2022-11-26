<?php

declare(strict_types=1);

namespace CometChat\Chat\Model;

use JMS\Serializer\Annotation as Serializer;

class MetaPagination
{
    /**
     * @var Pagination|null
     * @Serializer\Type("CometChat\Chat\Model\Pagination")
     */
    protected $paginaton;

    /**
     * @var Cursor|null
     * @Serializer\Type("CometChat\Chat\Model\Cursor")
     */
    protected $cursor;

    /**
     * @return Pagination|null
     */
    public function getPaginaton(): ?Pagination
    {
        return $this->paginaton;
    }

    /**
     * @return Cursor|null
     */
    public function getCursor(): ?Cursor
    {
        return $this->cursor;
    }
}
