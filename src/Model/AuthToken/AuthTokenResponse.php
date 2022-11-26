<?php

declare(strict_types=1);

namespace CometChat\Chat\Model\AuthToken;

use CometChat\Chat\Model\MetaPaginationResponse;
use JMS\Serializer\Annotation as Serializer;

class AuthTokenResponse
{
    /**
     * @var AuthToken
     * @Serializer\Type("CometChat\Chat\Model\AuthToken\AuthToken")
     */
    protected $data;

    /**
     * @return AuthToken
     */
    public function getData(): AuthToken
    {
        return $this->data;
    }
}
