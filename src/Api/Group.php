<?php

declare(strict_types=1);

namespace CometChat\Chat\Api;

use CometChat\Chat\Exception\UnknownErrorException;
use CometChat\Chat\Model\DeleteResponse;
use CometChat\Chat\Model\Group\CreateRequest;
use CometChat\Chat\Model\Group\CreateResponse;
use CometChat\Chat\Model\Group\GroupResponse;
use CometChat\Chat\Model\Group\ListQuery;
use CometChat\Chat\Model\Group\ListResponse;
use CometChat\Chat\Model\Group\UpdateRequest;
use Psr\Http\Client\ClientExceptionInterface;

class Group extends HttpApi
{
    /**
     * Lists all the groups of an app.
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
            '/groups',
            $query,
            $onBehalfOf ? ['onBehalfOf' => $onBehalfOf] : []
        );

        return $this->hydrateResponse($response, ListResponse::class, ['Default']);
    }

    /**
     * Retrieves group details for a specified GUID.
     *
     * @param  string                   $guid
     * @param  string|null              $onBehalfOf
     * @return GroupResponse
     * @throws UnknownErrorException
     * @throws ClientExceptionInterface
     */
    public function get(string $guid, string $onBehalfOf = null): GroupResponse
    {
        $response = $this->httpGet(
            sprintf('/groups/%s', $guid),
            $onBehalfOf ? ['onBehalfOf' => $onBehalfOf] : []
        );

        return $this->hydrateResponse($response, GroupResponse::class, ['Default']);
    }

    /**
     * Creates a new group.
     *
     * @param  CreateRequest            $request
     * @return CreateResponse
     * @throws ClientExceptionInterface
     * @throws UnknownErrorException
     */
    public function create(CreateRequest $request): CreateResponse
    {
        $response = $this->httpPost('/groups', $request);

        return $this->hydrateResponse($response, CreateResponse::class, ['Default']);
    }

    /**
     * Updates a group with the provided GUID.
     *
     * @param  string                   $guid
     * @param  UpdateRequest            $request
     * @return GroupResponse
     * @throws ClientExceptionInterface
     * @throws UnknownErrorException
     */
    public function update(string $guid, UpdateRequest $request): GroupResponse
    {
        $response = $this->httpPut(sprintf('/groups/%s', $guid), $request);

        return $this->hydrateResponse($response, GroupResponse::class, ['Default']);
    }

    /**
     * Deletes a group with a given GUID.
     *
     * @param  string                   $guid
     * @return DeleteResponse
     * @throws ClientExceptionInterface
     * @throws UnknownErrorException
     */
    public function delete(string $guid): DeleteResponse
    {
        $response = $this->httpDelete(sprintf('/groups/%s', $guid), []);

        return $this->hydrateResponse($response, DeleteResponse::class, ['Default']);
    }
}
