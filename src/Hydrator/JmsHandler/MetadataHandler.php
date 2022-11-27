<?php

declare(strict_types=1);

namespace CometChat\Chat\Hydrator\JmsHandler;

use CometChat\Chat\Model\Metadata;
use JMS\Serializer\Context;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;

class MetadataHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => Metadata::class,
                'method' => 'serializeToJson',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => Metadata::class,
                'method' => 'deserializeFromJson',
            ],
        ];
    }

    /**
     * @param  JsonSerializationVisitor $visitor
     * @param  Metadata|null            $value
     * @param  array                    $type
     * @param  Context                  $context
     * @return mixed
     */
    public function serializeToJson(JsonSerializationVisitor $visitor, $value, array $type, Context $context)
    {
        return $value ? $value->getData() : null;
    }

    public function deserializeFromJson(
        JsonDeserializationVisitor $visitor,
        $value,
        array $type,
        DeserializationContext $context
    ) {
        return new Metadata(is_array($value) ? $value : []);
    }
}
