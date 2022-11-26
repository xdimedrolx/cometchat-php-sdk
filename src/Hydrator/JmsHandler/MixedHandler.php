<?php

declare(strict_types=1);

namespace CometChat\Chat\Hydrator\JmsHandler;

use JMS\Serializer\Context;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;

class MixedHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'mixed',
                'method' => 'serializeMixedToJson',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'mixed',
                'method' => 'deserializeMixedToJson',
            ],
        ];
    }

    public function serializeMixedToJson(JsonSerializationVisitor $visitor, $value, array $type, Context $context)
    {
        return $value;
    }

    public function deserializeMixedToJson(
        JsonDeserializationVisitor $visitor,
        $value,
        array $type,
        DeserializationContext $context
    ) {
        if (\is_array($value)) {
            return $visitor->visitArray($value, $type);
        }

        if (is_numeric($value) && \is_string($value)) {
            if (stripos($value, '.') || stripos($value, ',')) {
                return \floatval($value);
            } else {
                return \intval($value);
            }
        }

        return $value;
    }
}
