<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\Role;

use CometChat\Chat\Assert;
use JMS\Serializer\Annotation as Serializer;

class Settings
{
    /**
     * @phpstan-var SettingValue::*|null
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $listUsers;

    /**
     * @phpstan-var SettingValue::*|null
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $sendMessagesTo;

    /**
     * @phpstan-return SettingValue::*|null
     */
    public function getListUsers(): ?string
    {
        return $this->listUsers;
    }

    /**
     * @param SettingValue::* $value
     */
    public function setListUsers(string $value): self
    {
        Assert::inArray($value, [SettingValue::ALL, SettingValue::FRIENDS_ONLY]);
        $this->listUsers = $value;
        return $this;
    }

    /**
     * @phpstan-return SettingValue::*|null
     */
    public function getSendMessagesTo(): ?string
    {
        return $this->sendMessagesTo;
    }

    /**
     * @phpstan-param SettingValue::* $value
     */
    public function setSendMessagesTo(string $value): self
    {
        Assert::inArray($value, [SettingValue::ALL, SettingValue::FRIENDS_ONLY]);
        $this->sendMessagesTo = $value;
        return $this;
    }
}
