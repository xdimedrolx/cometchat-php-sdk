<?php

declare(strict_types=1);

namespace CometChat\Chat\HttpClient;

use BenTools\QueryString\QueryString;
use BenTools\QueryString\Renderer\NativeRenderer;
use BenTools\QueryString\Renderer\QueryStringRendererInterface;

class QueryRenderer implements QueryStringRendererInterface
{
    /**
     * @var QueryStringRendererInterface
     */
    private $renderer;

    protected function __construct(QueryStringRendererInterface $renderer = null)
    {
        $this->renderer = $renderer;
    }

    public static function factory(QueryStringRendererInterface $renderer = null)
    {
        return new self($renderer ?? NativeRenderer::factory());
    }

    /**
     * {@inheritDoc}
     */
    public function render(QueryString $queryString): string
    {
        $separator = $this->getSeparator() ?? ini_get('arg_separator.output');
        $parts = [[]];

        foreach ($queryString->getParams() as $key => $value) {
            $parts[] = $this->getParts($key, $value);
        }

        return implode($separator, array_merge([], ...$parts));
    }

    /**
     * {@inheritDoc}
     */
    public function getEncoding(): int
    {
        return $this->renderer->getEncoding();
    }

    /**
     * {@inheritDoc}
     */
    public function withEncoding(int $encoding): self
    {
        return new self($this->renderer->withEncoding($encoding));
    }

    /**
     * {@inheritDoc}
     */
    public function getSeparator(): ?string
    {
        return $this->renderer->getSeparator();
    }

    /**
     * {@inheritDoc}
     */
    public function withSeparator(?string $separator): self
    {
        return new self($this->renderer->withSeparator($separator));
    }

    private function getParts($key, $value): array
    {
        if (is_iterable($value)) {
            $parts = [[]];
            foreach ($value as $sub) {
                $parts[] = $this->getParts($key, $sub);
            }

            return array_merge([], ...$parts);
        }

        if (\is_bool($value)) {
            return [$key.'='.($value ? 'true' : 'false')];
        }

        if (is_numeric($value)) {
            return [$key.'='.$value];
        }

        $encode = \PHP_QUERY_RFC1738 === $this->getEncoding() ? '\\urlencode' : '\\rawurlencode';

        return [$key.'='.\call_user_func($encode, (string) $value)];
    }
}
