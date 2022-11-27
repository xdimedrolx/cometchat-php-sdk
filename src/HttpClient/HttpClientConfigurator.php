<?php

declare(strict_types=1);

namespace CometChat\Chat\HttpClient;

use CometChat\Chat\HttpClient\Plugin\History;
use CometChat\Chat\HttpClient\Plugin\LoggerPlugin;
use CometChat\Chat\HttpClient\Plugin\ReplaceUriPlugin;
use CometChat\Chat\Version;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpAsyncClient;
use Http\Discovery\HttpAsyncClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Message\Formatter\FullHttpMessageFormatter;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;

final class HttpClientConfigurator
{
    /**
     * @var string
     */
    private $endpoint;

    /**
     * If debug is true we will send all the request to the endpoint without appending any path.
     *
     * @var bool
     */
    private $debug = false;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @var string|null
     */
    private $apiKey;

    /**
     * @var UriFactoryInterface
     */
    private $uriFactory;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var HttpAsyncClient
     */
    private $httpAsyncClient;

    /**
     * @var History
     */
    private $responseHistory;

    private $plugins = [];

    public function __construct(string $endpoint)
    {
        $this->endpoint = $endpoint;
        $this->responseHistory = new History();
    }

    public function createConfiguredClient(): PluginClient
    {
        return new PluginClient($this->getHttpClient(), $this->getPlugins());
    }

    public function createConfiguredAsyncClient(): PluginClient
    {
        return new PluginClient($this->getHttpAsyncClient(), $this->getPlugins());
    }

    public function getPlugins(): array
    {
        $endpoint = $this->getUriFactory()->createUri($this->endpoint);

        $plugins = [
            new Plugin\AddHostPlugin($endpoint),
            new Plugin\HistoryPlugin($this->responseHistory),
            new Plugin\HeaderDefaultsPlugin([
                'User-Agent' => 'PHP sdk/'.Version::VERSION,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]),
            ...$this->plugins,
        ];

        if ($endpoint->getPath() && '/' !== $endpoint->getPath()) {
            $plugins[] = new Plugin\AddPathPlugin($endpoint);
        }

        if ($this->getApiKey()) {
            $plugins[] = new Plugin\HeaderDefaultsPlugin([
                'apiKey' => $this->getApiKey(),
            ]);
        }

        if ($this->logger) {
            $plugins[] = new LoggerPlugin($this->logger, new FullHttpMessageFormatter(2048));
        }

        if ($this->debug) {
            $plugins[] = new ReplaceUriPlugin($endpoint);
        }

        return $plugins;
    }

    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;

        return $this;
    }

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function setLogger(?LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(?string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function addPlugins(array $plugins): self
    {
        $this->plugins = array_merge($this->plugins, $plugins);

        return $this;
    }

    private function getUriFactory(): UriFactoryInterface
    {
        if (null === $this->uriFactory) {
            $this->uriFactory = Psr17FactoryDiscovery::findUrlFactory();
        }

        return $this->uriFactory;
    }

    public function setUriFactory(UriFactoryInterface $uriFactory): self
    {
        $this->uriFactory = $uriFactory;

        return $this;
    }

    private function getHttpClient(): ClientInterface
    {
        if (null === $this->httpClient) {
            $this->httpClient = Psr18ClientDiscovery::find();
        }

        return $this->httpClient;
    }

    private function getHttpAsyncClient(): HttpAsyncClient
    {
        if (null === $this->httpAsyncClient) {
            $this->httpAsyncClient = HttpAsyncClientDiscovery::find();
        }

        return $this->httpAsyncClient;
    }

    public function setHttpClient(ClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    public function setHttpAsyncClient(HttpAsyncClient $httpAsyncClient): self
    {
        $this->httpAsyncClient = $httpAsyncClient;

        return $this;
    }

    public function getResponseHistory(): History
    {
        return $this->responseHistory;
    }
}
