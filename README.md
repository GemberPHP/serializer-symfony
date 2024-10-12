# 🫚 Gember Serializer: Symfony Serializer
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%5E8.3-8892BF.svg?style=flat)](http://www.php.net)

[Gember Event Sourcing](https://github.com/GemberPHP/event-sourcing) Serializer adapter based on [symfony/serializer](https://github.com/symfony/serializer).

> All external dependencies in Gember Event Sourcing are organized into separate packages,
> making it easy to swap out a vendor adapter for another.

## Installation
Install with Composer:
```bash
composer require gember/serializer-symfony
```

## Configuration
Bind this adapter to the `Serializer` interface in your service definitions.

### Examples

#### Vanilla PHP
```php
use Gember\SerializerSymfony\SymfonySerializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

$serializer = new SymfonySerializer(
    new Serializer(
        [
            new DateTimeNormalizer([
                DateTimeNormalizer::FORMAT_KEY => 'Y-m-d\TH:i:s.uP',
            ]),
            new ObjectNormalizer(),
        ],
        [
            new JsonEncoder(),
        ],
    ),
);
```

#### Symfony
It is recommended to use the [Symfony bundle](https://github.com/GemberPHP/event-sourcing-symfony-bundle) to configure Gember Event Sourcing.
With this bundle, the adapter is automatically set as the default for the Serializer.

If you're not using the bundle, you can either add the serializer to the existing stack of serializers or bind it directly to the `Serializer` interface.

Option 1: Add to the existing stack of serializers:
```yaml
Gember\SerializerSymfony\SymfonySerializer:
  arguments: 
    - '@serializer' # or any other Symfony Serializer definition of your choice 

Gember\EventSourcing\Util\Serialization\Serializer\SerializableDomainEvent\SerializableDomainEventSerializer: ~

Gember\EventSourcing\Util\Serialization\Serializer\Serializer:
  class: Gember\EventSourcing\Util\Serialization\Serializer\Stacked\StackedSerializer
  arguments:
    - [
        '@Gember\EventSourcing\Util\Serialization\Serializer\SerializableDomainEvent\SerializableDomainEventSerializer',
        '@Gember\SerializerSymfony\SymfonySerializer' # added to stack of serializers
    ]
```

Option 2: Bind directly to `Serializer` interface:
```yaml
Gember\EventSourcing\Util\Serialization\Serializer\Serializer:
  class: Gember\SerializerSymfony\SymfonySerializer
  arguments:
    - '@serializer' # or any other Symfony Serializer definition of your choice
```