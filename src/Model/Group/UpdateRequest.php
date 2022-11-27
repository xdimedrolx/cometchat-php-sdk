<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\Group;

use CometChat\Chat\Assert;
use CometChat\Chat\Model\Metadata;
use JMS\Serializer\Annotation as Serializer;

class UpdateRequest
{
    /**
     * Name of the group.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $name;

    /**
     * Type of the group. Can be public, password or private.
     *
     * @var string|null
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
     * The UID that you wish to make the owner of the group.
     * This field will be ignored if onBehalfOf parameter in the header is present in the request.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $owner;

    /**
     * @var Metadata
     * @Serializer\Type("CometChat\Chat\Model\Metadata")
     * @Serializer\SkipWhenEmpty()
     */
    protected $metadata;

    /**
     * Updates tags of a group.
     *
     * @var string[]
     * @Serializer\SkipWhenEmpty()
     * @Serializer\Type("array<string>")
     */
    protected $tags = [];

    /**
     * The unsettable group attributes are icon, description and metadata.
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
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @phpstan-param GroupType::* $type
     * @param string|null $password
     */
    public function setTypeAndPassword(string $type, ?string $password = null): void
    {
        Assert::inArray($type, [GroupType::PUBLIC, GroupType::PROTECTED, GroupType::PASSWORD]);

        $this->type = $type;
        $this->password = $password;

        if (GroupType::PASSWORD === $type) {
            Assert::notEmpty($password);
        }
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
