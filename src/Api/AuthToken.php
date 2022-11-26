<?php

declare(strict_types=1);

namespace CometChat\Chat\Api;

use CometChat\Chat\Exception\UnknownErrorException;
use CometChat\Chat\Model\AuthToken\AuthTokenResponse;
use CometChat\Chat\Model\AuthToken\CreateRequest;
use Psr\Http\Client\ClientExceptionInterface;

class AuthToken extends HttpApi
{
    /**
     * Creates auth token for a user with the specified UID.
     *
     * @param  string                   $uid
     * @param  CreateRequest            $request
     * @return AuthTokenResponse
     * @throws ClientExceptionInterface
     * @throws UnknownErrorException
     */
    public function create(string $uid, CreateRequest $request): AuthTokenResponse
    {
        $response = $this->httpPost(sprintf('/users/%s/auth_tokens', $uid), $request);

        return $this->hydrateResponse($response, AuthTokenResponse::class, ['Default']);
    }
}
