<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\Role;

use CometChat\Chat\Model\Metadata;
use JMS\Serializer\Annotation as Serializer;

class Role
{
    /**
     * A unique identifier for the role.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $role;

    /**
     * Friendly name of the role.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $name;

    /**
     * Description of the role.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $description;

    /**
     * Additional information about the role.
     *
     * @var Metadata
     * @Serializer\Type("CometChat\Chat\Model\Metadata")
     */
    protected $metadata;

    /**
     * Role settings that is used for restricting list users/send message.
     *
     * @var Settings
     * @Serializer\Type("CometChat\Chat\Model\Role\Settings")
     */
    protected $settings;

    /**
     * @var int
     * @Serializer\Type("int")
     */
    protected $createdAt;

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return Metadata
     */
    public function getMetadata(): Metadata
    {
        return $this->metadata;
    }

    /**
     * @return Settings
     */
    public function getSettings(): Settings
    {
        return $this->settings;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }
}