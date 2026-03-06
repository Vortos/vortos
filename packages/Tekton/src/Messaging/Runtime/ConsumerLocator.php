<?php

declare(strict_types=1);

namespace Fortizan\Tekton\Messaging\Runtime;

use Fortizan\Tekton\Messaging\Contract\ConsumerInterface;
use Fortizan\Tekton\Messaging\Contract\ConsumerLocatorInterface;
use Fortizan\Tekton\Messaging\Driver\Kafka\Factory\KafkaConsumerFactory;

/**
 * Default ConsumerLocator implementation backed by KafkaConsumerFactory.
 *
 * Swap the ConsumerLocatorInterface binding in your container to replace
 * the entire consumer resolution strategy — for testing, alternative
 * drivers, or multi-driver routing.
 */
final class ConsumerLocator implements ConsumerLocatorInterface
{
    public function __construct(
        private KafkaConsumerFactory $factory
    ){
    }

    public function get(string $consumerName): ConsumerInterface
    {
        return $this->factory->create($consumerName);
    }
}