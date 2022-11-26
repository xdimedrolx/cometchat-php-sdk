<?php

declare(strict_types=1);

namespace CometChat\Chat\Api;

use function BenTools\QueryString\query_string;

use BenTools\QueryString\Renderer\QueryStringRendererInterface;
use CometChat\Chat\Exception\HttpClientException;
use CometChat\Chat\Exception\HttpServerException;
use CometChat\Chat\Exception\HydrationException;
use CometChat\Chat\Exception\UnknownErrorException;
use CometChat\Chat\HttpClient\QueryRenderer;
use CometChat\Chat\HttpClient\RequestBuilder;
use CometChat\Chat\Hydrator\Hydrator;
use CometChat\Chat\Hydrator\NoopHydrator;
use CometChat\Chat\Model\QueryParams;
use Http\Client\HttpAsyncClient;
use Http\Promise\Promise;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Psr\Http\Client as Psr18;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class HttpApi
{
    /**
     * The HTTP client.
     *
     * @var HttpAsyncClient
     */
    protected $httpAsyncClient;

    /**
     * @var Hydrator|null
     */
    protected $hydrator;

    /**
     * @var RequestBuilder
     */
    protected $requestBuilder;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var QueryStringRendererInterface
     */
    protected $queryRenderer;

    public function __construct(
        HttpAsyncClient $httpAsyncClient,
        RequestBuilder $requestBuilder,
        Hydrator $hydrator,
        Serializer $serializer
    ) {
        $this->httpAsyncClient = $httpAsyncClient;
        $this->requestBuilder = $requestBuilder;
        $this->serializer = $serializer;
        if (!$hydrator instanceof NoopHydrator) {
            $this->hydrator = $hydrator;
        }
        $this->queryRenderer = QueryRenderer::factory();
    }

    /**
     * @template T
     * @param  class-string<T>       $class
     * @return T|ResponseInterface
     * @throws UnknownErrorException
     * @throws HttpClientException
     * @throws HttpServerException
     * @throws HydrationException
     */
    protected function hydrateResponse(ResponseInterface $response, string $class, array $groups = ['Default'])
    {
        if (null === $this->hydrator) {
            return $response;
        }

        if (!\in_array($response->getStatusCode(), [200, 201, 204], true)) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, $class, $groups);
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string            $path           Request path
     * @param array|QueryParams $parameters     GET parameters
     * @param array             $requestHeaders Request Headers
     *
     * @throws Psr18\ClientExceptionInterface
     * @throws UnknownErrorException
     */
    protected function httpGet(string $path, $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpGetAsync($path, $parameters, $requestHeaders)->wait();
    }

    /**
     * Send an GET request as an asynchronous operation.
     *
     * @param string            $path           Request path
     * @param array|QueryParams $parameters     GET parameters
     * @param array             $requestHeaders Request Headers
     *
     * @throws UnknownErrorException
     * @throws HttpServerException
     * @throws HttpClientException
     * @throws \Exception
     */
    protected function httpGetAsync(string $path, $parameters = [], array $requestHeaders = []): Promise
    {
        if (\is_object($parameters)) {
            $parameters = $this->createQueryParams($parameters);
        }

        if (\is_array($parameters) && \count($parameters) > 0) {
            $path .= '?'.query_string($parameters)->withRenderer($this->queryRenderer);
        }

        return $this->doAsyncRequest(
            $this->requestBuilder->create('GET', $path, $requestHeaders)
        );
    }

    /**
     * Send a POST request.
     *
     * @param string $path           Request path
     * @param mixed  $body           Request body
     * @param array  $requestHeaders Request headers
     *
     * @throws Psr18\ClientExceptionInterface
     */
    protected function httpPost(
        string $path,
               $body,
        array $requestHeaders = [],
        array $groups = ['Default']
    ): ResponseInterface {
        return $this->httpPostRawAsync($path, $this->createRequestBody($body, $groups), $requestHeaders)->wait();
    }

    /**
     * Send a POST request as an asynchronous operation.
     *
     * @param string $path           Request path
     * @param mixed  $body           Request body
     * @param array  $requestHeaders Request headers
     *
     * @throws UnknownErrorException
     * @throws HttpServerException
     * @throws HttpClientException
     * @throws \Exception
     */
    protected function httpPostAsync(
        string $path,
               $body,
        array $requestHeaders = [],
        array $groups = ['Default']
    ): Promise {
        return $this->httpPostRawAsync($path, $this->createRequestBody($body, $groups), $requestHeaders);
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string       $path           Request path
     * @param array|string $body           Request body
     * @param array        $requestHeaders Request headers
     *
     * @throws Psr18\ClientExceptionInterface
     */
    protected function httpPostRaw(string $path, $body, array $requestHeaders = []): ResponseInterface
    {
        return $this->httpPostRawAsync($path, $body, $requestHeaders)->wait();
    }

    /**
     * Send a POST request as an asynchronous operation with raw data.
     *
     * @param string       $path           Request path
     * @param array|string $body           Request body
     * @param array        $requestHeaders Request headers
     *
     * @throws UnknownErrorException
     * @throws HttpServerException
     * @throws HttpClientException
     * @throws \Exception
     */
    protected function httpPostRawAsync(string $path, $body, array $requestHeaders = []): Promise
    {
        return $this->doAsyncRequest(
            $this->requestBuilder->create('POST', $path, $requestHeaders, $body)
        );
    }

    /**
     * Send a PUT request.
     *
     * @param string $path Request path
     * @param $body
     * @param array $requestHeaders Request headers
     *
     * @throws Psr18\ClientExceptionInterface
     */
    protected function httpPut(
        string $path,
               $body,
        array $requestHeaders = [],
        array $groups = ['Default']
    ): ResponseInterface {
        return $this->httpPutAsync($path, $body, $requestHeaders, $groups)->wait();
    }

    /**
     * Send a PUT request as an asynchronous operation.
     *
     * @param string $path Request path
     * @param $body
     * @param array $requestHeaders Request headers
     *
     * @throws UnknownErrorException
     * @throws HttpServerException
     * @throws HttpClientException
     * @throws \Exception
     */
    protected function httpPutAsync(
        string $path,
               $body,
        array $requestHeaders = [],
        array $groups = ['Default']
    ): Promise {
        return $this->doAsyncRequest(
            $this->requestBuilder->create(
                'PUT',
                $path,
                $requestHeaders,
                $this->createRequestBody($body, $groups)
            )
        );
    }

    /**
     * Send a DELETE request.
     *
     * @param string     $path           Request path
     * @param array|null $body           DELETE body
     * @param array      $requestHeaders Request headers
     *
     * @throws Psr18\ClientExceptionInterface
     */
    protected function httpDelete(string $path, $body, array $requestHeaders = []): ResponseInterface
    {
        return $this->httpDeleteAsync($path, $body, $requestHeaders)->wait();
    }

    /**
     * Send a DELETE request as an asynchronous operation.
     *
     * @param $body
     *
     * @throws UnknownErrorException
     * @throws HttpServerException
     * @throws HttpClientException
     * @throws \Exception
     */
    protected function httpDeleteAsync(string $path, $body, array $requestHeaders = []): Promise
    {
        return $this->doAsyncRequest(
            $this->requestBuilder->create('DELETE', $path, $requestHeaders, $this->createRequestBody($body))
        );
    }

    /**
     * @throws UnknownErrorException
     * @throws HttpServerException
     * @throws HttpClientException
     * @throws \Exception
     */
    private function doAsyncRequest(RequestInterface $request): Promise
    {
        try {
            return $this->httpAsyncClient->sendAsyncRequest($request)
                ->then(function (ResponseInterface $response) {
                    return $this->handleAsyncResponse($response);
                }, function (\Exception $e) {
                    if ($e instanceof Psr18\NetworkExceptionInterface) {
                        throw HttpServerException::networkError($e);
                    }
                    throw $e;
                })
            ;
        } catch (Psr18\NetworkExceptionInterface $e) {
            throw HttpServerException::networkError($e);
        }
    }

    /**
     * @throws UnknownErrorException
     * @throws HttpServerException
     * @throws HttpClientException
     */
    private function handleAsyncResponse(ResponseInterface $response): ResponseInterface
    {
        if (!\in_array($response->getStatusCode(), [200, 201, 204], true)) {
            $this->handleErrors($response);
        }

        return $response;
    }

    /**
     * Throw the correct exception for this error.
     *
     * @throws UnknownErrorException
     * @throws HttpClientException
     * @throws HttpServerException
     */
    private function handleErrors(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        switch ($statusCode) {
            case 400:
                throw HttpClientException::badRequest($response);
            case 401:
                throw HttpClientException::unauthorized($response);
            case 402:
                throw HttpClientException::requestFailed($response);
            case 403:
                throw HttpClientException::forbidden($response);
            case 404:
                throw HttpClientException::notFound($response);
            case 409:
                throw HttpClientException::alreadyExists($response);
            case 413:
                throw HttpClientException::payloadTooLarge($response);
            case 500 > $statusCode:
                throw new HttpClientException($response->getReasonPhrase(), $response->getStatusCode(), $response);
            case 500 <= $statusCode:
                throw HttpServerException::serverError($response, $statusCode);
            default:
                throw new UnknownErrorException();
        }
    }

    /**
     * @param mixed $body Request body
     */
    private function createRequestBody($body, array $groups = ['Default']): string
    {
        if (empty($body)) {
            return '';
        }

        return $this->serializer->serialize(
            $body,
            'json',
            SerializationContext::create()->setGroups($groups)->setSerializeNull(true)
        );
    }

    protected function createQueryParams($query, bool $excludeNullValues = true): array
    {
        $parameters = $this->serializer->toArray(
            $query,
            SerializationContext::create()->setSerializeNull(!$excludeNullValues)
        );

        // fix empty values
        return array_filter($parameters, function ($v) {
            if (\is_array($v) && !\count($v)) {
                return false;
            }

            return true;
        });
    }

    public function generateUrl(string $path, $parameters = null, bool $excludeNullValues = true): string
    {
        if (\is_object($parameters)) {
            $parameters = $this->createQueryParams($parameters, $excludeNullValues);
        }

        if (\is_array($parameters) && \count($parameters) > 0) {
            $path .= '?'.query_string($parameters)->withRenderer($this->queryRenderer);
        }

        return $path;
    }
}
