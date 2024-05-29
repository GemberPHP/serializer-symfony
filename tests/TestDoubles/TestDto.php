<?php

declare(strict_types=1);

namespace Gember\SerializerSymfony\Test\TestDoubles;

use DateTimeImmutable;

final readonly class TestDto
{
    public function __construct(
        public string $id,
        public int $integer,
        public float $float,
        public DateTimeImmutable $dateTimeImmutable,
    ) {}
}
