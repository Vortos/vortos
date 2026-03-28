<?php

declare(strict_types=1);

namespace App\User\Infrastructure;

use App\User\Domain\Event\UserCreatedEvent;
use Fortizan\Tekton\Messaging\Attribute\MessagingConfig;
use Fortizan\Tekton\Messaging\Attribute\RegisterTransport;
use Fortizan\Tekton\Messaging\Attribute\RegisterProducer;
use Fortizan\Tekton\Messaging\Attribute\RegisterConsumer;
use Fortizan\Tekton\Messaging\Driver\Kafka\Definition\KafkaTransportDefinition;
use Fortizan\Tekton\Messaging\Driver\Kafka\Definition\KafkaProducerDefinition;
use Fortizan\Tekton\Messaging\Driver\Kafka\Definition\KafkaConsumerDefinition;
use Fortizan\Tekton\Messaging\Retry\RetryPolicy;

#[MessagingConfig]
final class UserMessagingConfig
{
    #[RegisterTransport]
    public function userTransport(): KafkaTransportDefinition
    {
        return KafkaTransportDefinition::create('user.events')
            ->dsn('kafka://kafka:9092')
            ->topic('user.events')
            ->partitions(3)
            ->replicationFactor(1);
    }

    #[RegisterProducer]
    public function userProducer(): KafkaProducerDefinition
    {
        return KafkaProducerDefinition::create('user.events')
            ->transport('user.events')
            ->publishes(UserCreatedEvent::class)
            ->outbox(false)
            ->linger(5);
    }

    #[RegisterConsumer]
    public function userConsumer(): KafkaConsumerDefinition
    {
        return KafkaConsumerDefinition::create('user.events')
            ->groupId('user-service')
            ->parallelism(1)
            ->batchSize(1)
            ->retry(RetryPolicy::exponential(attempts: 3, initialDelayMs: 500))
            ->dlq('user.events.dlq')
            ->offsetReset('earliest');
    }
}
