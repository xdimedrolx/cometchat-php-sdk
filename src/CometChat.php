<?php

declare(strict_types=1);

namespace CometChat\Chat;

use CometChat\Chat\Api\AuthToken;
use CometChat\Chat\Api\Group;
use CometChat\Chat\Api\Role;
use CometChat\Chat\Api\User;
use CometChat\Chat\HttpClient\HttpClientConfigurator;
use CometChat\Chat\HttpClient\Plugin\History;
use CometChat\Chat\HttpClient\RequestBuilder;
use CometChat\Chat\Hydrator\Hydrator;
use CometChat\Chat\Hydrator\JmsHandler\MetadataHandler;
use CometChat\Chat\Hydrator\JmsHandler\MixedHandler;
use CometChat\Chat\Hydrator\JmsModelHydrator;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\PsrCachedReader;
use Http\Client\Common\PluginClient;
use Http\Client\HttpAsyncClient;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Expression\ExpressionEvaluator;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Metadata\Cache\PsrCacheAdapter;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class CometChat
{
    public const ENDPOINT = 'https://{appId}.api-{region}.cometchat.io/v3';

    /**
     * @var string|null
     */
    private $apiKey;

    /**
     * @var HttpAsyncClient|PluginClient
     */
    private $httpAsyncClient;

    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * This is an object that holds the last response from the API.
     *
     * @var History
     */
    private $responseHistory;

    /**
     * @phpstan-var array{
     *     user: User,
     *     group: Group,
     *     authToken: AuthToken,
     *     role: Role
     * }
     * @var array
     */
    private $apiClients = [];

    /**
     * @var bool
     */
    private $debug;

    public function __construct(
        HttpClientConfigurator $configurator,
        ?CacheItemPoolInterface $serializerCache = null,
        ?Hydrator $hydrator = null,
        ?RequestBuilder $requestBuilder = null,
        bool $debug = false
    ) {
        $this->debug = $debug;

        $this->serializer = $this->buildSerializer($serializerCache);
        $this->requestBuilder = $requestBuilder ?: new RequestBuilder();
        $this->hydrator = $hydrator ?: new JmsModelHydrator($this->serializer);

        $this->httpAsyncClient = $configurator->createConfiguredAsyncClient();
        $this->apiKey = $configurator->getApiKey();
        $this->responseHistory = $configurator->getResponseHistory();

        $this->buildApiClients();
    }

    public static function create(
        string $appId,
        string $region,
        ?string $apiKey = null,
        ?LoggerInterface $logger = null,
        ?CacheItemPoolInterface $serializerCache = null,
        array $httpPlugins = [],
        bool $debug = false,
        ?string $endpoint = null
    ): self {
        $endpoint = strtr($endpoint ?: self::ENDPOINT, ['{appId}' => $appId, '{region}' => $region]);

        $httpClientConfigurator = (new HttpClientConfigurator($endpoint))
            ->setApiKey($apiKey)
            ->setEndpoint($endpoint)
            ->addPlugins($httpPlugins)
            ->setLogger($logger)
        ;

        return new self(
            $httpClientConfigurator,
            $serializerCache,
            null,
            null,
            $debug
        );
    }

    public function getLastResponse(): ?ResponseInterface
    {
        return $this->responseHistory->getLastResponse();
    }

    public function user(): User
    {
        return $this->apiClients['user'];
    }

    public function group(): Group
    {
        return $this->apiClients['group'];
    }

    public function authToken(): AuthToken
    {
        return $this->apiClients['authToken'];
    }

    public function role(): Role
    {
        return $this->apiClients['role'];
    }

    private function buildApiClients(): void
    {
        $this->apiClients = [
            'authToken' => $this->makeApi(AuthToken::class),
            'user' => $this->makeApi(User::class),
            'group' => $this->makeApi(Group::class),
            'role' => $this->makeApi(Role::class),
        ];
    }

    /**
     * @template T
     * @param  class-string<T> $class
     * @return T
     */
    private function makeApi(string $class)
    {
        return new $class(
            $this->httpAsyncClient,
            $this->requestBuilder,
            $this->hydrator,
            $this->serializer
        );
    }

    private function buildSerializer(?CacheItemPoolInterface $serializerCache = null): Serializer
    {
        $builder = SerializerBuilder::create()
            ->setSerializationContextFactory(function () {
                return SerializationContext::create()
                    ->setSerializeNull(false)
                    ->setGroups(['Default'])
                ;
            })
            ->setDeserializationContextFactory(function () {
                return DeserializationContext::create()
                    ->setGroups(['Default'])
                ;
            })
            ->setPropertyNamingStrategy(
                new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy())
            )
            ->addDefaultHandlers()
            ->configureHandlers(function (HandlerRegistry $registry) {
                $registry->registerSubscribingHandler(new MixedHandler());
                $registry->registerSubscribingHandler(new MetadataHandler());
            })
            ->setDebug($this->debug)
        ;

        if ($serializerCache) {
            $builder->setMetadataCache(
                new PsrCacheAdapter('cometChat', $serializerCache)
            );
            $builder->setAnnotationReader(new PsrCachedReader(
                new AnnotationReader(),
                $serializerCache,
                $this->debug
            ));
        }

        $expressionLanguage = new ExpressionLanguage($serializerCache ?? null);
        $builder->setExpressionEvaluator(new ExpressionEvaluator($expressionLanguage));

        return $builder->build();
    }
}
