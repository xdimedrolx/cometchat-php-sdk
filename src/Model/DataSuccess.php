<?php

declare(strict_types=1);

namespace CometChat\Chat\Model;

use JMS\Serializer\Annotation as Serializer;

class DataSuccess
{
    /**
     * @var bool
     * @Serializer\Type("bool")
     */
    protected $success;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $message;

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }
}
