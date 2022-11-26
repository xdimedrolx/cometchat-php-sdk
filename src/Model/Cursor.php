<?php

declare(strict_types=1);

namespace CometChat\Chat\Model;

use JMS\Serializer\Annotation as Serializer;

class Cursor
{
    /**
     * @var int|null
     * @Serializer\Type("int")
     */
    protected $updatedAt;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $affix;

    /**
     * @return int|null
     */
    public function getUpdatedAt(): ?int
    {
        return $this->updatedAt;
    }

    /**
     * @return string|null
     */
    public function getAffix(): ?string
    {
        return $this->affix;
    }
}
