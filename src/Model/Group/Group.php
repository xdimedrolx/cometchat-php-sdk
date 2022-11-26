<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\Group;

use CometChat\Chat\Model\Metadata;
use JMS\Serializer\Annotation as Serializer;

class Group
{
    /**
     * A unique identifier for a group.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $guid;

    /**
     * Name of the group.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $name;

    /**
     * URL to picture of the group.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $icon;

    /**
     * Type of the group. Can be public, password or private.
     *
     * @phpstan-var GroupType::*|null
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $type;

    /**
     * @var Metadata
     * @Serializer\Type("CometChat\Chat\Model\Metadata")
     */
    protected $metadata;

    /**
     * @var int
     * @Serializer\Type("int")
     */
    protected $createdAt;

    /**
     * The UID that you wish to make owner of the group.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $owner;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $conversationId;

    /**
     * @var int
     * @Serializer\Type("int")
     */
    protected $membersCount = 0;

    /**
     * @var bool
     * @Serializer\Type("bool")
     */
    protected $isBanned = true;

    /**
     * @return string
     */
    public function getGuid(): string
    {
        return $this->guid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @phpstan-return GroupType::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return Metadata
     */
    public function getMetadata(): Metadata
    {
        return $this->metadata;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @return string|null
     */
    public function getOwner(): ?string
    {
        return $this->owner;
    }

    /**
     * @return string|null
     */
    public function getConversationId(): ?string
    {
        return $this->conversationId;
    }

    /**
     * @return int
     */
    public function getMembersCount(): int
    {
        return $this->membersCount;
    }

    /**
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->isBanned;
    }
}
