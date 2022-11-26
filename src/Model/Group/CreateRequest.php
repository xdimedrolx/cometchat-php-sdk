<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\Group;

use CometChat\Chat\Assert;
use CometChat\Chat\Model\Metadata;
use JMS\Serializer\Annotation as Serializer;

class CreateRequest
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
     * Type of the group. Can be public, password or private.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $type;

    /**
     * A password required to join the group with type password.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $password;

    /**
     * An URL for a group icon.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $icon;

    /**
     * Description about the group.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $description;

    /**
     * The UID that you wish to make owner of the group.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $owner;

    /**
     * List of tags to identify specific groups.
     *
     * @var string[]
     * @Serializer\Type("array<string>")
     */
    protected $tags = [];

    /**
     * @var Metadata
     * @Serializer\Type("CometChat\Chat\Model\Metadata")
     */
    protected $metadata;

    public function __construct(string $guid, string $name, string $type, ?string $password = null)
    {
        Assert::inArray($type, [GroupType::PASSWORD, GroupType::PUBLIC, GroupType::PROTECTED]);

        if (GroupType::PASSWORD === $type) {
            Assert::notEmpty($password);
        }

        $this->guid = $guid;
        $this->name = $name;
        $this->type = $type;
        $this->password = $password;
    }

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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param string|null $icon
     */
    public function setIcon(?string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getOwner(): ?string
    {
        return $this->owner;
    }

    /**
     * @param string|null $owner
     */
    public function setOwner(?string $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param string[] $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return Metadata
     */
    public function getMetadata(): Metadata
    {
        return $this->metadata;
    }

    /**
     * @param Metadata $metadata
     */
    public function setMetadata(Metadata $metadata): void
    {
        $this->metadata = $metadata;
    }
}
