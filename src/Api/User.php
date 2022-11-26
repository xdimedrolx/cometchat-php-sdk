<?php

declare(strict_types=1);

namespace CometChat\Chat\Api;

use CometChat\Chat\Exception\UnknownErrorException;
use CometChat\Chat\Model\User\CreateRequest;
use CometChat\Chat\Model\User\CreateResponse;
use CometChat\Chat\Model\User\ListQuery;
use CometChat\Chat\Model\User\ListResponse;
use CometChat\Chat\Model\User\UpdateRequest;
use CometChat\Chat\Model\User\UserResponse;
use Psr\Http\Client\ClientExceptionInterface;

class User extends HttpApi
{
    /**
     * Lists all the users of an app.
     *
     * @param  ListQuery                $query
     * @param  string|null              $onBehalfOf
     * @return ListResponse
     * @throws UnknownErrorException
     * @throws ClientExceptionInterface
     */
    public function list(ListQuery $query, string $onBehalfOf = null): ListResponse
    {
        $response = $this->httpGet(
            '/users',
            $query,
            $onBehalfOf ? ['onBehalfOf' => $onBehalfOf] : []
        );

        return $this->hydrateResponse($response, ListResponse::class, ['Default']);
    }

    /**
     * Retrieves user details for a specified UID.
     *
     * @param  string                   $uid
     * @param  string|null              $onBehalfOf
     * @return UserResponse
     * @throws UnknownErrorException
     * @throws ClientExceptionInterface
     */
    public function get(string $uid, string $onBehalfOf = null): UserResponse
    {
        $response = $this->httpGet(
            sprintf('/users/%s', $uid),
            $onBehalfOf ? ['onBehalfOf' => $onBehalfOf] : []
        );

        return $this->hydrateResponse($response, UserResponse::class, ['Default']);
    }

    /**
     * Creates a new user.
     *
     * @param  CreateRequest            $request
     * @return CreateResponse
     * @throws ClientExceptionInterface
     * @throws UnknownErrorException
     */
    public function create(CreateRequest $request): CreateResponse
    {
        $response = $this->httpPost('/users', $request);

        return $this->hydrateResponse($response, CreateResponse::class, ['Default']);
    }

    /**
     * Updates a user with the provided UID.
     *
     * @param  string                   $uid
     * @param  UpdateRequest            $request
     * @return UserResponse
     * @throws ClientExceptionInterface
     * @throws UnknownErrorException
     */
    public function update(string $uid, UpdateRequest $request): UserResponse
    {
        $response = $this->httpPut(sprintf('/users/%s', $uid), $request);

        return $this->hydrateResponse($response, UserResponse::class, ['Default']);
    }
}
