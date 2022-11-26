<?php

declare(strict_types=1);

namespace CometChat\Chat\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Replaces a URI with a new one. Good for debugging.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ReplaceUriPlugin implements Plugin
{
    use Plugin\VersionBridgePlugin;

    /**
     * @var UriInterface
     */
    private $uri;

    public function __construct(UriInterface $uri)
    {
        $this->uri = $uri;
    }

    public function doHandleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $request = $request->withUri($this->uri);

        return $next($request);
    }
}
