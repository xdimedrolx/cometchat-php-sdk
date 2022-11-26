<?php

declare(strict_types=1);

namespace CometChat\Chat\Tests\Unit;

use CometChat\Chat\Hydrator\JmsHandler\MetadataHandler;
use CometChat\Chat\Hydrator\JmsHandler\MixedHandler;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Expression\ExpressionEvaluator;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class CometChatTestCase extends TestCase
{
    public function getFixture(string $path): StreamInterface
    {
        return $this->psr17Factory()->createStreamFromFile(__DIR__.'/fixtures/'.$path);
    }

    public function psr17Factory(): Psr17Factory
    {
        return new Psr17Factory();
    }

    public function buildSerializer(): Serializer
    {
        return SerializerBuilder::create()
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
            ->setDebug(false)
            ->setExpressionEvaluator(new ExpressionEvaluator(new ExpressionLanguage(null)))
            ->build()
        ;
    }
}
