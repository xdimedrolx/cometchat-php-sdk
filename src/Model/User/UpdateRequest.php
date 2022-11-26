<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\User;

use CometChat\Chat\Model\Metadata;
use JMS\Serializer\Annotation as Serializer;

class UpdateRequest
{
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
     * URL to profile page.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $link;

    /**
     * @var Metadata
     * @Serializer\Type("CometChat\Chat\Model\Metadata")
     * @Serializer\SkipWhenEmpty()
     */
    protected $metadata;

    /**
     * User role of the user for role based access control.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $role;

    /**
     * Updates tags of a specific group.
     *
     * @var string[]
     * @Serializer\SkipWhenEmpty()
     * @Serializer\Type("array<string>")
     */
    protected $tags = [];

    /**
     * The unsettable user attributes are avatar, link and metadata.
     *
     * @var string[]
     * @Serializer\SkipWhenEmpty()
     * @Serializer\Type("array")
     */
    protected $unset = [];

    public function __construct()
    {
        $this->metadata = new Metadata();
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
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
     * @param string[] $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return string[]
     */
    public function getUnset(): array
    {
        return $this->unset;
    }

    /**
     * @param string[] $unset
     */
    public function setUnset(array $unset): void
    {
        $this->unset = $unset;
    }
}
