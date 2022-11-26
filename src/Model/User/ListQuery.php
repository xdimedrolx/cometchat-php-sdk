<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\User;

use CometChat\Chat\Model\QueryParams;
use JMS\Serializer\Annotation as Serializer;

class ListQuery extends QueryParams
{
    /**
     * Searches for given keyword in user's list (either UID, name or email).
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    public $searchKey;

    /**
     * Searches for specified keyword in name,UID or both.
     *
     * @var array
     * @Serializer\Type("array")
     */
    public $searchIn = [];

    /**
     * Searches for given keyword in user's list (either UID, name or email).
     *
     * @var UserStatus::AVAILABLE|UserStatus::OFFLINE|null
     * @Serializer\Type("string")
     */
    public $status;

    /**
     * Fetches users count.
     *
     * @var bool
     * @Serializer\Type("bool")
     */
    public $count;

    /**
     * Number of users to be fetched in a request. The default value is 100 and the maximum value is 1000.
     *
     * @var int
     * @Serializer\Type("int")
     */
    public $perPage = 100;

    /**
     * Page number.
     *
     * @var int
     * @Serializer\Type("int")
     */
    public $page = 1;

    /**
     * Retrieves user list based on role.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    public $role;

    /**
     * Includes tags in the response.
     *
     * @var bool
     * @Serializer\Type("bool")
     */
    public $withTags;

    /**
     * Fetches only those users that have these tags.
     *
     * @var array
     * @Serializer\Type("array")
     */
    public $tags = [];

    /**
     * Fetches users based on multiple roles.
     *
     * @var array
     * @Serializer\Type("array")
     */
    public $roles = [];

    /**
     * Fetches all the deactivated users of an app.
     *
     * @var bool
     * @Serializer\Type("bool")
     */
    public $onlyDeactivated;

    /**
     * Fetches all the users including deactivated users.
     *
     * @var bool
     * @Serializer\Type("bool")
     */
    public $withDeactivated;
}
