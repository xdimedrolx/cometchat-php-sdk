<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\Group;

use CometChat\Chat\Model\QueryParams;
use JMS\Serializer\Annotation as Serializer;

class ListQuery extends QueryParams
{
    /**
     * Searches for given keyword in group's list (either GUID, name or email).
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    public $searchKey;

    /**
     * Searches for specified keyword in name,GUID or both.
     *
     * @var array
     * @Serializer\Type("array")
     */
    public $searchIn = [];

    /**
     * Determines whether to fetch the groups either before or after createdAt/updatedAt timestamp.
     * Possible values are append(after) and prepend(before).
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    public $affix;

    /**
     * Fetches the groups list after a particular updatedAt timestamp.
     *
     * @var int|null
     * @Serializer\Type("int")
     */
    public $updatedAt;

    /**
     * Number of groups to be fetched in a request. The default value is 100 and the maximum value is 1000.
     *
     * @var int
     * @Serializer\Type("int")
     */
    public $perPage = 100;

    /**
     * Includes those groups that have tags.
     *
     * @var bool
     * @Serializer\Type("bool")
     */
    public $withTags = false;

    /**
     * Page number.
     *
     * @var int
     * @Serializer\Type("int")
     */
    public $page = 1;

    /**
     * Fetches only those users that have these tags.
     *
     * @var array
     * @Serializer\Type("array")
     */
    public $tags = [];

    /**
     * Fetches groups based on group type(public, protected, password).
     *
     * @phpstan-var GroupType::*|null
     * @var string|null
     * @Serializer\Type("string")
     */
    public $type;

    /**
     * Fetches groups based on multiple types.
     *
     * @phpstan-var list<GroupType::*>
     * @var string[]
     * @Serializer\Type("array")
     */
    public $types = [];

    /**
     * Fetches all the deactivated users of an app.
     *
     * @var bool
     * @Serializer\Type("bool")
     */
    public $onlyDeactivated;

    /**
     * Fetches all the joined groups of a user.
     * This will work only with onBehalfOf parameter in header.
     *
     * @var bool
     * @Serializer\Type("bool")
     */
    public $hasJoined = false;
}
