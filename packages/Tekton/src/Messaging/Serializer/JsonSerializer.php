<?php

declare(strict_types=1);

namespace Fortizan\Tekton\Messaging\Serializer;

use Fortizan\Tekton\Messaging\Contract\DomainEventInterface;
use Fortizan\Tekton\Messaging\Contract\SerializerInterface;
use Fortizan\Tekton\Messaging\Serializer\Exception\DeserializationException;
use Fortizan\Tekton\Messaging\Serializer\Exception\SerializationException;
use JsonException;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use RuntimeException;

/**
 * JSON serializer for domain events.
 *
 * Serializes all public properties to JSON, including a '_class' marker
 * for type identification. Deserializes via constructor reflection,
 * supporting nested value objects as long as they follow the same
 * constructor promotion pattern.
 *
 * For events with complex types (enums, custom collections, deeply nested
 * structures), implement a custom SerializerInterface instead.
 */
final class JsonSerializer implements SerializerInterface
{
    public function supports(string $format): bool
    {
        return $format === 'json';
    }

    public function serialize(DomainEventInterface $event): string
    {
        $properties = new ReflectionClass($event)->getProperties(ReflectionProperty::IS_PUBLIC);

        $data = [];
        foreach ($properties as $property) {
            $data[$property->getName()] = $property->getValue($event);
        }

        $data['_class'] = get_class($event);

        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new SerializationException(
                message: "Failed to serialize event of class '" . get_class($event) . "': " . $e->getMessage(),
                previous: $e
            );
        }
    }

    public function deserialize(string $payload, string $eventClass): DomainEventInterface
    {
        try {

            $data = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
            unset($data['_class']);

            $reflClass = new ReflectionClass($eventClass);
            $constructor = $reflClass->getConstructor();

            if ($constructor === null) {
                return $reflClass->newInstance();
            }

            $params = $constructor->getParameters();

            $args = [];
            foreach ($params as $param) {
                $paramName = $param->getName();

                if (isset($data[$paramName])) {

                    $paramType = $param->getType();

                    if($paramType instanceof ReflectionNamedType &&
                        !$paramType->isBuiltin() &&
                        is_array($data[$paramName])){

                        $nestedClass = $paramType->getName();

                        $args[] = $this->deserialize(json_encode($data[$paramName]), $nestedClass);
                    }else{
                        $args[] = $data[$paramName];
                    }

                } else if ($param->isOptional()) {
                    $args[] = $param->getDefaultValue();
                } else {
                    throw DeserializationException::forPayload(
                        $payload,
                        $eventClass,
                        new RuntimeException("Missing required parameter '{$paramName}'")
                    );
                }
            }

            $instance = $reflClass->newInstanceArgs($args);
            return $instance;
        } catch (\Throwable $e) {
            throw DeserializationException::forPayload($payload, $eventClass, $e);
        }
    }
}
