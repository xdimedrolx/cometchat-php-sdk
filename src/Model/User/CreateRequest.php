<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\User;

use CometChat\Chat\Model\Metadata;
use JMS\Serializer\Annotation as Serializer;

class CreateRequest
{
    /**
     * Unique identifier of the user. Please refer to https://prodocs.cometchat.com/docs/concepts#uid.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $uid;

    /**
     * Display name of the user.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $name;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $avatar;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $link;

    /**
     * @var Metadata
     * @Serializer\Type("CometChat\Chat\Model\Metadata")
     */
    protected $metadata;

    /**
     * @var string|null
     * @phpstan-var UserStatus::AVAILABLE|UserStatus::OFFLINE|null
     * @Serializer\Type("string")
     */
    protected $status;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $role;

    /**
     * @var string[]
     * @Serializer\Type("array<string>")
     */
    protected $tags = [];

    /**
     * @var bool
     * @Serializer\Type("bool")
     */
    protected $withAuthToken = true;

    public function __construct(string $uid, string $name)
    {
        $this->uid = $uid;
        $this->name = $name;
        $this->metadata = new Metadata();
    }

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
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
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @return bool
     */
    public function isWithAuthToken(): bool
    {
        return $this->withAuthToken;
    }

    /**
     * @param string|null $avatar
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @param string|null $role
     */
    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

    /**
     * @param string|null $link
     */
    public function setLink(?string $link): void
    {
        $this->link = $link;
    }

    /**
     * @param Metadata $metadata
     */
    public function setMetadata(Metadata $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @param null $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @param string[] $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @param bool $withAuthToken
     */
    public function setWithAuthToken(bool $withAuthToken): void
    {
        $this->withAuthToken = $withAuthToken;
    }
}
