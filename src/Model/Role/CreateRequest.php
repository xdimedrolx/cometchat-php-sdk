<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\Role;

use CometChat\Chat\Model\Metadata;
use JMS\Serializer\Annotation as Serializer;

class CreateRequest
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

    public function __construct(string $role, string $name)
    {
        $this->role = $role;
        $this->name = $name;
        $this->metadata = new Metadata();
        $this->settings = new Settings();
    }

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
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
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
     * @return Settings
     */
    public function getSettings(): Settings
    {
        return $this->settings;
    }

    /**
     * @param Settings $settings
     */
    public function setSettings(Settings $settings): void
    {
        $this->settings = $settings;
    }
}