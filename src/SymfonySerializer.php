<?php

declare(strict_types=1);

namespace Gember\SerializerSymfony;

use Gember\DependencyContracts\Util\Serialization\Serializer\SerializationFailedException;
use Gember\DependencyContracts\Util\Serialization\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Override;
use Throwable;

final readonly class SymfonySerializer implements Serializer
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {}

    /**
     * @throws SerializationFailedException
     */
    #[Override]
    public function serialize(object $object): string
    {
        try {
            return $this->serializer->serialize($object, JsonEncoder::FORMAT);
        } catch (Throwable $exception) {
            throw SerializationFailedException::withException($exception);
        }
    }

    /**
     * Any failure is a SerializationFailedException, as the contract declares: a StackedSerializer then tries
     * its next serializer, instead of stopping at a Symfony exception.
     *
     * @throws SerializationFailedException
     */
    #[Override]
    public function deserialize(string $payload, string $className): object
    {
        try {
            /** @var object */
            return $this->serializer->deserialize($payload, $className, JsonEncoder::FORMAT);
        } catch (Throwable $exception) {
            throw SerializationFailedException::withException($exception);
        }
    }
}
