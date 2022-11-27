<?php

declare(strict_types=1);

namespace CometChat\Chat\Tests\Unit\Api;

use CometChat\Chat\Api\HttpApi;
use CometChat\Chat\Api\User;
use CometChat\Chat\Exception\HttpClientException;
use CometChat\Chat\Exception\HttpServerException;
use Http\Client\Exception\NetworkException;
use Psr\Http\Message\RequestInterface;

class FakeApi extends HttpApi
{
    public function list(): void
    {
        $response = $this->httpGet('/test');
        $this->hydrateResponse($response, User::class);
    }
}

class HttpApiTest extends HttpApiTestCase
{
    public function testNetworkException()
    {
        $this->getMockClient()->addException(
            new NetworkException(
                'Server is currently unreachable',
                $this->createMock(RequestInterface::class)
            )
        );

        $sut = $this->buildApiWithMockClient(FakeApi::class);

        $this->expectException(HttpServerException::class);

        $sut->list();
    }

    /** @dataProvider clientExceptionDataProvider */
    public function testClientException(string $fixture, int $code, string $expectedException)
    {
        $this->getMockClient()->addResponse(
            $this->createResponse($this->getFixture($fixture), $code)
        );

        $sut = $this->buildApiWithMockClient(FakeApi::class);

        $this->expectException($expectedException);

        $sut->list();
    }

    public function clientExceptionDataProvider(): \Generator
    {
        yield 'Bad request' => [
            'error_badRequest_400.json',
            400,
            HttpClientException::class,
        ];
        yield 'Invalid Api Key' => [
            'error_invalidApiKey_403.json',
            403,
            HttpClientException::class,
        ];
        yield 'Not found' => [
            'error_notFound_404.json',
            404,
            HttpClientException::class,
        ];
    }
}
