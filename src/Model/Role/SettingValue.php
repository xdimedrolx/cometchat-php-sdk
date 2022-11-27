<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\Role;

use CometChat\Chat\Model\Metadata;
use JMS\Serializer\Annotation as Serializer;

interface SettingValue
{
    public const ALL = 'all';
    public const FRIENDS_ONLY = 'friendsOnly';
}
