<?php

declare(strict_types=1);

namespace Gember\SerializerSymfony\Test;

use DateTimeImmutable;
use DateTimeZone;
use Gember\SerializerSymfony\SymfonySerializer;
use Gember\SerializerSymfony\Test\TestDoubles\TestDto;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @internal
 */
final class SymfonySerializerTest extends TestCase
{
    private SymfonySerializer $serializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = new SymfonySerializer(
            new Serializer(
                [
                    new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d\TH:i:s.uP']),
                    new ObjectNormalizer(),
                ],
                [
                    new JsonEncoder(),
                ],
            ),
        );
    }

    #[Test]
    public function itShouldSerializeObjectIntoPayload(): void
    {
        $serialized = $this->serializer->serialize(new TestDto(
            'da11f500-ab9b-49e6-96eb-83b9f3669a47',
            1234,
            23.500,
            new DateTimeImmutable('2024-05-29 10:30:00.345654', new DateTimeZone('America/Los_Angeles')),
        ));

        self::assertSame(
            '{"id":"da11f500-ab9b-49e6-96eb-83b9f3669a47","integer":1234,"float":23.5,"dateTimeImmutable":"2024-05-29T10:30:00.345654-07:00"}',
            $serialized,
        );
    }

    #[Test]
    public function itShouldDeserializePayloadIntoObject(): void
    {
        $dto = $this->serializer->deserialize(
            '{"id":"da11f500-ab9b-49e6-96eb-83b9f3669a47","integer":1234,"float":23.5,"dateTimeImmutable":"2024-05-29T10:30:00.345654-07:00"}',
            TestDto::class,
        );

        self::assertEquals(
            new TestDto(
                'da11f500-ab9b-49e6-96eb-83b9f3669a47',
                1234,
                23.500,
                new DateTimeImmutable('2024-05-29 10:30:00.345654', new DateTimeZone('America/Los_Angeles')),
            ),
            $dto,
        );
    }
}
