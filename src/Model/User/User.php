<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\User;

use CometChat\Chat\Model\Metadata;
use JMS\Serializer\Annotation as Serializer;

class User
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $uid;

    /**
     * Display name of the user.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $name;

    /**
     * URL to profile picture of the user.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $avatar;

    /**
     * @var Metadata
     * @Serializer\Type("CometChat\Chat\Model\Metadata")
     */
    protected $metadata;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $email;

    /**
     * @var string|null
     * @phpstan-var UserStatus::AVAILABLE|UserStatus::OFFLINE|null
     * @Serializer\Type("string")
     */
    protected $status;

    /**
     * User role of the user for role based access control.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $role;

    /**
     * A list of tags to identify specific users.
     *
     * @var string[]
     * @Serializer\Type("array<string>")
     */
    protected $tags = [];

    /**
     * @var int
     * @Serializer\Type("int")
     */
    protected $createdAt;

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @return Metadata
     */
    public function getMetadata(): Metadata
    {
        return $this->metadata;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @return string|null
     * @phpstan-return UserStatus::AVAILABLE|UserStatus::OFFLINE|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
