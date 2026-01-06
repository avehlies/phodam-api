# Creating a Provider Bundle for ramsey/uuid

This guide demonstrates how to create a ProviderBundle for the `ramsey/uuid` library that provides providers for `UuidInterface` and named providers for different UUID versions.

## Overview

The `ramsey/uuid` library supports multiple UUID versions, each suited for different use cases:
- **UUID v4**: Random UUIDs (most common, suitable for general-purpose identifiers)
- **UUID v1/v6**: Time-based UUIDs (useful for time-ordered database indexes)
- **UUID v3/v5**: Name-based UUIDs (deterministic, useful for generating consistent UUIDs from names)
- **UUID v7**: Unix epoch time-based (modern alternative to v1 with better sortability)

Creating a provider bundle allows Phodam to automatically generate appropriate UUIDs for `UuidInterface` types in your test data.

## Prerequisites

- PHP 8.4+
- `phodam/phodam-api` package
- `ramsey/uuid` package (^4.0 or ^5.0)

## Project Setup

### Composer Configuration

```json
{
    "name": "your-vendor/phodam-ramsey-uuid",
    "description": "Phodam providers for ramsey/uuid",
    "type": "library",
    "require": {
        "php": "^8.4",
        "phodam/phodam-api": "^2.0",
        "ramsey/uuid": "^4.0|^5.0"
    },
    "autoload": {
        "psr-4": {
            "YourVendor\\PhodamRamseyUuid\\": "src/"
        }
    }
}
```

### Directory Structure

```
phodam-ramsey-uuid/
├── src/
│   └── Provider/
│       ├── UuidInterfaceProvider.php
│       ├── UuidV1Provider.php
│       ├── UuidV4Provider.php
│       ├── UuidV5Provider.php
│       ├── UuidV7Provider.php
│       └── RamseyUuidProviderBundle.php
├── composer.json
└── README.md
```

## Creating Providers

### Default Provider

Create a default provider for `UuidInterface` that generates UUID v4. This serves as the fallback when no named provider is specified, ensuring all `UuidInterface` types receive valid UUIDs without explicit configuration.

```php
<?php

namespace YourVendor\PhodamRamseyUuid\Provider;

use Phodam\Provider\PhodamProvider;
use Phodam\Provider\TypedProviderInterface;
use Phodam\Provider\ProviderContextInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[PhodamProvider(type: UuidInterface::class)]
class UuidInterfaceProvider implements TypedProviderInterface
{
    public function create(ProviderContextInterface $context): UuidInterface
    {
        $overrides = $context->getOverrides();
        
        if (isset($overrides['uuid'])) {
            if ($overrides['uuid'] instanceof UuidInterface) {
                return $overrides['uuid'];
            }
            if (is_string($overrides['uuid'])) {
                return Uuid::fromString($overrides['uuid']);
            }
        }
        
        return Uuid::uuid4();
    }
}
```

### Named Version Providers

Create named providers for specific UUID versions. Named providers allow you to explicitly request a particular UUID version when generating test data, which is useful when testing version-specific behavior or when your application requires a specific UUID format.

```php
<?php

namespace YourVendor\PhodamRamseyUuid\Provider;

use Phodam\Provider\PhodamProvider;
use Phodam\Provider\TypedProviderInterface;
use Phodam\Provider\ProviderContextInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

// UUID v4 (random) - explicit named provider
#[PhodamProvider(type: UuidInterface::class, name: 'v4')]
class UuidV4Provider implements TypedProviderInterface
{
    public function create(ProviderContextInterface $context): UuidInterface
    {
        $overrides = $context->getOverrides();
        
        if (isset($overrides['uuid'])) {
            if ($overrides['uuid'] instanceof UuidInterface) {
                return $overrides['uuid'];
            }
            if (is_string($overrides['uuid'])) {
                return Uuid::fromString($overrides['uuid']);
            }
        }
        
        return Uuid::uuid4();
    }
}

// UUID v1 (time-based) - useful for time-ordered identifiers
#[PhodamProvider(type: UuidInterface::class, name: 'v1')]
class UuidV1Provider implements TypedProviderInterface
{
    public function create(ProviderContextInterface $context): UuidInterface
    {
        $overrides = $context->getOverrides();
        $config = $context->getConfig();
        
        $node = $overrides['node'] ?? $config['node'] ?? null;
        $clockSeq = $overrides['clockSeq'] ?? $config['clockSeq'] ?? null;
        
        if ($node !== null && $clockSeq !== null) {
            return Uuid::uuid1($node, $clockSeq);
        } elseif ($node !== null) {
            return Uuid::uuid1($node);
        }
        
        return Uuid::uuid1();
    }
}

// UUID v5 (name-based with SHA-1) - deterministic UUIDs from names
#[PhodamProvider(type: UuidInterface::class, name: 'v5')]
class UuidV5Provider implements TypedProviderInterface
{
    public function create(ProviderContextInterface $context): UuidInterface
    {
        $overrides = $context->getOverrides();
        $config = $context->getConfig();
        
        $namespace = $overrides['namespace'] ?? $config['namespace'] ?? Uuid::NAMESPACE_DNS;
        $name = $overrides['name'] ?? $config['name'] ?? 'default-name';
        
        if (is_string($namespace)) {
            $namespace = Uuid::fromString($namespace);
        } elseif (!$namespace instanceof UuidInterface) {
            throw new \InvalidArgumentException(
                'Namespace must be a UuidInterface instance or a valid UUID string'
            );
        }
        
        return Uuid::uuid5($namespace, $name);
    }
}

// UUID v7 (Unix epoch time-based) - modern time-ordered UUIDs
#[PhodamProvider(type: UuidInterface::class, name: 'v7')]
class UuidV7Provider implements TypedProviderInterface
{
    public function create(ProviderContextInterface $context): UuidInterface
    {
        return Uuid::uuid7();
    }
}
```

## Creating the Provider Bundle

The bundle registers all providers with Phodam. This centralizes provider registration and makes it easy to distribute your UUID providers as a reusable package.

```php
<?php

namespace YourVendor\PhodamRamseyUuid\Provider;

use Phodam\Provider\ProviderBundleInterface;

class RamseyUuidProviderBundle implements ProviderBundleInterface
{
    public function getProviders(): array
    {
        return [
            UuidInterfaceProvider::class,
            UuidV1Provider::class,
            UuidV4Provider::class,
            UuidV5Provider::class,
            UuidV7Provider::class,
        ];
    }

    public function getTypeDefinitions(): array
    {
        return [];
    }
}
```

Phodam automatically scans these classes for `PhodamProvider` attributes and registers them based on the type and name specified in each attribute.

## Usage

### Basic Usage

Register the bundle and use the default provider or named providers:

```php
<?php

use Phodam\PhodamSchema;
use YourVendor\PhodamRamseyUuid\Provider\RamseyUuidProviderBundle;
use Ramsey\Uuid\UuidInterface;

$schema = PhodamSchema::withDefaults();
$phodam = $schema->getPhodam();
$schema->registerBundle(RamseyUuidProviderBundle::class);

// Default provider (v4)
$uuid = $phodam->create(UuidInterface::class);

// Named provider for specific version
$uuidV7 = $phodam->create(UuidInterface::class, 'v7');
```

### Using Overrides

Overrides allow you to specify exact values for test scenarios where you need predictable UUIDs:

```php
$customUuid = $phodam->create(
    UuidInterface::class,
    null,
    ['uuid' => '123e4567-e89b-12d3-a456-426614174000']
);
```

### Using Configuration

Configuration is useful for name-based UUIDs (v3/v5) where you need to provide namespace and name parameters:

```php
use Ramsey\Uuid\Uuid;

$uuidV5 = $phodam->create(
    UuidInterface::class,
    'v5',
    null,
    [
        'namespace' => Uuid::NAMESPACE_DNS,
        'name' => 'example.com'
    ]
);
```

### In Tests

Using UUID providers in tests ensures your test data uses valid UUIDs without manual generation:

```php
<?php

namespace YourApp\Tests;

use Phodam\PhodamSchema;
use YourVendor\PhodamRamseyUuid\Provider\RamseyUuidProviderBundle;
use Ramsey\Uuid\UuidInterface;

class UserTest extends TestCase
{
    private Phodam $phodam;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $schema = PhodamSchema::withDefaults();
        $this->phodam = $schema->getPhodam();
        $schema->registerBundle(RamseyUuidProviderBundle::class);
    }
    
    public function testUserCreation(): void
    {
        $user = new User();
        $user->setId($this->phodam->create(UuidInterface::class));
        
        $this->assertInstanceOf(UuidInterface::class, $user->getId());
    }
}
```

## Summary

This guide demonstrates creating a provider bundle for `ramsey/uuid` that enables automatic UUID generation in Phodam. The bundle includes a default provider for `UuidInterface` (generating v4 UUIDs) and named providers for specific versions (v1, v4, v5, v7). Providers use PHP attributes to declare their types and names, and the bundle centralizes registration for easy distribution. This approach simplifies test data generation by automatically providing valid UUIDs without manual instantiation, while supporting overrides and configuration for version-specific requirements.
