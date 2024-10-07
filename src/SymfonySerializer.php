<?php

declare(strict_types=1);

namespace Gember\SerializerSymfony;

use Gember\EventSourcing\Util\Serialization\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Override;

final readonly class SymfonySerializer implements Serializer
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {}

    #[Override]
    public function serialize(object $object): string
    {
        return $this->serializer->serialize($object, JsonEncoder::FORMAT);
    }

    #[Override]
    public function deserialize(string $payload, string $className): object
    {
        /** @var object */
        return $this->serializer->deserialize($payload, $className, JsonEncoder::FORMAT);
    }
}
