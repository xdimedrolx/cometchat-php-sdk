<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\Group;

interface GroupType
{
    public const PUBLIC = 'public';
    public const PROTECTED = 'protected';
    public const PASSWORD = 'password';
}
