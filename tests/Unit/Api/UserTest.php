<?php

declare(strict_types=1);

namespace CometChat\Chat\Tests\Unit\Api;

use CometChat\Chat\Api\User;
use CometChat\Chat\Exception\HttpClientException;
use CometChat\Chat\Model\User\CreateRequest;
use Http\Message\RequestMatcher\RequestMatcher;

class UserTest extends HttpApiTestCase
{
    public function testCreate(): void
    {
        $dto = new CreateRequest('superhero6', 'Barry Allen');

        $this->getMockClient()->on(
            new RequestMatcher('/users', null, 'POST'),
            $this->createResponse($this->getFixture('createUser_200.json'))
        );

        $sut = $this->buildApiWithMockClient(User::class);

        $user = $sut->create($dto)->getData();

        $this->assertNotNull($user);
        $this->assertEquals($dto->getUid(), $user->getUid());
        $this->assertEquals($dto->getName(), $user->getName());
        $this->assertNotNull($user->getAvatar());
        $this->assertNotNull($user->getStatus());
        $this->assertNotNull($user->getRole());
        $this->assertNotEmpty($user->getTags());
        $this->assertNotNull($user->getAuthToken());
        $this->assertNotEmpty($user->getMetadata());
        // @phpstan-ignore-next-line
        $this->assertNotNull($user->getMetadata()->externalId);
    }

    public function testGetUserWhenItExists(): void
    {
        $this->getMockClient()->on(
            new RequestMatcher('/users/superhero6', null, 'GET'),
            $this->createResponse($this->getFixture('getUser_200.json'))
        );

        $sut = $this->buildApiWithMockClient(User::class);

        $user = $sut->get('superhero6')->getData();

        $this->assertNotNull($user);
        $this->assertEquals('superhero6', $user->getUid());
        $this->assertNotNull($user->getName());
        $this->assertNotNull($user->getAvatar());
        $this->assertNotNull($user->getStatus());
        $this->assertNotNull($user->getRole());
    }

    public function testGetWhenItDoesNotExist(): void
    {
        $this->getMockClient()->on(
            new RequestMatcher('/users/superhero', null, 'GET'),
            $this->createResponse($this->getFixture('getUser_404.json'), 404)
        );

        $sut = $this->buildApiWithMockClient(User::class);

        $this->expectException(HttpClientException::class);

        $sut->get('superhero');
    }
}
