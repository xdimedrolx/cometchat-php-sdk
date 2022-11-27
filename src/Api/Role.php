<?php

declare(strict_types=1);

namespace CometChat\Chat\Api;

use CometChat\Chat\Exception\UnknownErrorException;
use CometChat\Chat\Model\Role\CreateRequest;
use CometChat\Chat\Model\Role\ListResponse;
use CometChat\Chat\Model\Role\RoleResponse;
use CometChat\Chat\Model\Role\UpdateRequest;
use Psr\Http\Client\ClientExceptionInterface;

class Role extends HttpApi
{
    /**
     * Lists the user roles.
     *
     * @return ListResponse
     * @throws UnknownErrorException
     * @throws ClientExceptionInterface
     */
    public function list(): ListResponse
    {
        $response = $this->httpGet('/roles');

        return $this->hydrateResponse($response, ListResponse::class, ['Default']);
    }

    /**
     * Retrieves role details for a given role.
     *
     * @param  string                   $role
     * @return RoleResponse
     * @throws ClientExceptionInterface
     * @throws UnknownErrorException
     */
    public function get(string $role): RoleResponse
    {
        $response = $this->httpGet(sprintf('/roles/%s', $role));

        return $this->hydrateResponse($response, RoleResponse::class, ['Default']);
    }

    /**
     * Creates a new user role.
     *
     * @param  CreateRequest            $request
     * @return RoleResponse
     * @throws ClientExceptionInterface
     * @throws UnknownErrorException
     */
    public function create(CreateRequest $request): RoleResponse
    {
        $response = $this->httpPost('/roles', $request);

        return $this->hydrateResponse($response, RoleResponse::class, ['Default']);
    }

    /**
     * Updates a user with the provided UID.
     *
     * @param  string                   $role
     * @param  UpdateRequest            $request
     * @return RoleResponse
     * @throws ClientExceptionInterface
     * @throws UnknownErrorException
     */
    public function update(string $role, UpdateRequest $request): RoleResponse
    {
        $response = $this->httpPut(sprintf('/users/%s', $role), $request);

        return $this->hydrateResponse($response, RoleResponse::class, ['Default']);
    }
}
