<?php

declare(strict_types=1);

namespace CometChat\Chat\Model;

use JMS\Serializer\Annotation as Serializer;

class Error
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $message;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $devMessage;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $source;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $code;

    /**
     * @var array<string, string[]>
     * @Serializer\Type("array")
     */
    protected $details = [];

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string|null
     */
    public function getDevMessage(): ?string
    {
        return $this->devMessage;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return array<string, string[]>
     */
    public function getDetails(): array
    {
        return $this->details;
    }
}
