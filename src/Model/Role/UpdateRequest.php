<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\Role;

use CometChat\Chat\Model\Metadata;
use JMS\Serializer\Annotation as Serializer;

class UpdateRequest
{
    /**
     * Friendly name of the role.
     *
     * @var string|null
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
     * @Serializer\SkipWhenEmpty
     */
    protected $metadata;

    /**
     * Role settings that is used for restricting list users/send message.
     *
     * @var Settings
     * @Serializer\Type("CometChat\Chat\Model\Role\Settings")
     * @Serializer\SkipWhenEmpty
     */
    protected $settings;

    /**
     * The unsettable user attributes are description and metadata.
     *
     * @var string[]
     * @Serializer\SkipWhenEmpty()
     * @Serializer\Type("array")
     */
    protected $unset = [];

    public function __construct()
    {
        $this->metadata = new Metadata();
        $this->settings = new Settings();
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