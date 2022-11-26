<?php

declare(strict_types=1);

namespace CometChat\Chat\Hydrator\JmsHandler;

use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use JMS\Serializer\XmlSerializationVisitor;

class DateTimeHandler implements SubscribingHandlerInterface
{
    /**
     * @var string
     */
    private $defaultFormat;

    /**
     * @var \DateTimeZone
     */
    private $defaultTimezone;
    /**
     * @var \DateTimeZone
     */
    private $serializeTimezone;

    /**
     * @var bool
     */
    private $xmlCData;

    private $deserializeCache = [];

    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        $methods = [];
        $types = ['DateTime', 'DateTimeImmutable', 'DateInterval'];

        foreach (['json', 'xml'] as $format) {
            foreach ($types as $type) {
                $methods[] = [
                    'type' => $type,
                    'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                    'format' => $format,
                    'priority' => 10000,
                ];
                $methods[] = [
                    'type' => $type,
                    'format' => $format,
                    'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                    'method' => 'serialize'.$type,
                    'priority' => 10000,
                ];
            }

            $methods[] = [
                'type' => 'DateTimeInterface',
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'format' => $format,
                'method' => 'deserializeDateTimeFrom'.ucfirst($format),
                'priority' => 10000,
            ];
        }

        return $methods;
    }

    public function __construct(
        string $defaultFormat = \DateTime::ATOM,
        string $defaultTimezone = 'UTC',
        bool $xmlCData = true
    ) {
        $this->defaultFormat = $defaultFormat;
        $this->defaultTimezone = new \DateTimeZone($defaultTimezone);
        $this->serializeTimezone = new \DateTimeZone('UTC');
        $this->xmlCData = $xmlCData;
    }

    /**
     * @return \DOMCdataSection|\DOMText|mixed
     */
    private function serializeDateTimeInterface(
        SerializationVisitorInterface $visitor,
        \DateTimeInterface $date,
        array $type,
        SerializationContext $context
    ) {
        if ($visitor instanceof XmlSerializationVisitor && false === $this->xmlCData) {
            return $visitor->visitSimpleString($date->format($this->getFormat($type)), $type);
        }

        $format = $this->getFormat($type);

        if (preg_match('/(H|i|s)/', $format)) {
            $date = (clone $date)->setTimezone($this->serializeTimezone);
        }

        if ('U' === $format) {
            return $visitor->visitInteger((int) $date->format($format), $type);
        }

        return $visitor->visitString($date->format($this->getFormat($type)), $type);
    }

    /**
     * @return \DOMCdataSection|\DOMText|mixed
     */
    public function serializeDateTime(
        SerializationVisitorInterface $visitor,
        \DateTime $date,
        array $type,
        SerializationContext $context
    ) {
        return $this->serializeDateTimeInterface($visitor, $date, $type, $context);
    }

    /**
     * @return \DOMCdataSection|\DOMText|mixed
     */
    public function serializeDateTimeImmutable(
        SerializationVisitorInterface $visitor,
        \DateTimeImmutable $date,
        array $type,
        SerializationContext $context
    ) {
        return $this->serializeDateTimeInterface($visitor, $date, $type, $context);
    }

    /**
     * @return \DOMCdataSection|\DOMText|mixed
     */
    public function serializeDateInterval(
        SerializationVisitorInterface $visitor,
        \DateInterval $date,
        array $type,
        SerializationContext $context
    ) {
        $iso8601DateIntervalString = $this->format($date);

        if ($visitor instanceof XmlSerializationVisitor && false === $this->xmlCData) {
            return $visitor->visitSimpleString($iso8601DateIntervalString, $type);
        }

        return $visitor->visitString($iso8601DateIntervalString, $type);
    }

    private function isDataXmlNull($data): bool
    {
        $attributes = $data->attributes('xsi', true);

        return isset($attributes['nil'][0]) && 'true' === (string) $attributes['nil'][0];
    }

    public function deserializeDateTimeFromXml(
        DeserializationVisitorInterface $visitor,
        $data,
        array $type
    ): ?\DateTimeInterface {
        if ($this->isDataXmlNull($data)) {
            return null;
        }

        return $this->parseDateTime($data, $type);
    }

    public function deserializeDateTimeImmutableFromXml(
        DeserializationVisitorInterface $visitor,
        $data,
        array $type
    ): ?\DateTimeInterface {
        if ($this->isDataXmlNull($data)) {
            return null;
        }

        return $this->parseDateTime($data, $type, true);
    }

    public function deserializeDateIntervalFromXml(
        DeserializationVisitorInterface $visitor,
        $data,
        array $type
    ): ?\DateInterval {
        if ($this->isDataXmlNull($data)) {
            return null;
        }

        return $this->parseDateInterval((string) $data);
    }

    public function deserializeDateTimeFromJson(
        DeserializationVisitorInterface $visitor,
        $data,
        array $type
    ): ?\DateTimeInterface {
        if (null === $data) {
            return null;
        }

        return $this->parseDateTime($data, $type);
    }

    public function deserializeDateTimeImmutableFromJson(
        DeserializationVisitorInterface $visitor,
        $data,
        array $type
    ): ?\DateTimeInterface {
        if (null === $data) {
            return null;
        }

        return $this->parseDateTime($data, $type, true);
    }

    public function deserializeDateIntervalFromJson(
        DeserializationVisitorInterface $visitor,
        $data,
        array $type
    ): ?\DateInterval {
        if (null === $data) {
            return null;
        }

        return $this->parseDateInterval($data);
    }

    private function parseDateTime($data, array $type, bool $immutable = false): \DateTimeInterface
    {
        $timezone = !empty($type['params'][1]) ? new \DateTimeZone($type['params'][1]) : $this->defaultTimezone;
        $formats = $this->getDeserializationFormats($type);

        $formatTried = [];
        foreach ($formats as $format) {
            $key = sprintf(
                '%d/%s/%s/%s',
                $immutable,
                $format,
                $timezone->getName(),
                (string) $data
            );

            if (isset($this->deserializeCache[$key])) {
                $datetime = clone $this->deserializeCache[$key];
            } else {
                if ($immutable) {
                    $datetime = \DateTimeImmutable::createFromFormat($format, (string) $data, $timezone);
                } else {
                    $datetime = \DateTime::createFromFormat($format, (string) $data, $timezone);
                }

                if ($datetime instanceof \DateTimeInterface) {
                    $this->deserializeCache[$key] = $datetime;
                }
            }

            if (false !== $datetime) {
//                if ('U' === $format) {
                $datetime = (clone $datetime)->setTimezone($timezone);
//                }

                return $datetime;
            }

            $formatTried[] = $format;
        }

        throw new RuntimeException(sprintf('Invalid datetime "%s", expected one of the format %s.', $data, '"'.implode('", "', $formatTried).'"'));
    }

    private function parseDateInterval(string $data): \DateInterval
    {
        $dateInterval = null;
        try {
            $f = 0.0;
            if (preg_match('~\.\d+~', $data, $match)) {
                $data = str_replace($match[0], '', $data);
                $f = (float) $match[0];
            }
            $dateInterval = new \DateInterval($data);
            $dateInterval->f = $f;
        } catch (\Throwable $e) {
            throw new RuntimeException(sprintf('Invalid dateinterval "%s", expected ISO 8601 format', $data), 0, $e);
        }

        return $dateInterval;
    }

    private function getDeserializationFormats(array $type): array
    {
        if (isset($type['params'][2])) {
            return \is_array($type['params'][2]) ? $type['params'][2] : [$type['params'][2]];
        }

        return [$this->getFormat($type)];
    }

    private function getFormat(array $type): string
    {
        return $type['params'][0] ?? $this->defaultFormat;
    }

    public function format(\DateInterval $dateInterval): string
    {
        $format = 'P';

        if (0 < $dateInterval->y) {
            $format .= $dateInterval->y.'Y';
        }

        if (0 < $dateInterval->m) {
            $format .= $dateInterval->m.'M';
        }

        if (0 < $dateInterval->d) {
            $format .= $dateInterval->d.'D';
        }

        if (0 < $dateInterval->h || 0 < $dateInterval->i || 0 < $dateInterval->s) {
            $format .= 'T';
        }

        if (0 < $dateInterval->h) {
            $format .= $dateInterval->h.'H';
        }

        if (0 < $dateInterval->i) {
            $format .= $dateInterval->i.'M';
        }

        if (0 < $dateInterval->s) {
            $format .= $dateInterval->s.'S';
        }

        if ('P' === $format) {
            $format = 'P0DT0S';
        }

        return $format;
    }
}
