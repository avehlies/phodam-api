# Creating Provider Bundles

This guide explains how to create and register provider bundles in Phodam. Provider bundles allow you to package multiple providers together and register them all at once.

## Table of Contents

1. [Overview](#overview)
2. [Creating Providers](#creating-providers)
3. [Creating a Provider Bundle](#creating-a-provider-bundle)
4. [Registering a Bundle in Phodam](#registering-a-bundle-in-phodam)
5. [Complete Example](#complete-example)
6. [Advanced Topics](#advanced-topics)

## Overview

A provider bundle is a class that implements `ProviderBundleInterface` and groups together multiple providers. Instead of registering each provider individually, you can register an entire bundle with a single call.

The bundle interface requires two methods:
- `getProviders(): array` - Returns an array of provider class names
- `getTypeDefinitions(): array` - Returns an array of type definitions (optional)

## Creating Providers

Before creating a bundle, you need to create individual providers. Providers are classes that implement `ProviderInterface` or `TypedProviderInterface` and are decorated with attributes.

### Basic Provider Example

Here's a simple provider that creates instances of a `User` class:

```php
<?php

namespace MyLibrary\Provider;

use Phodam\Provider\PhodamProvider;
use Phodam\Provider\TypedProviderInterface;
use Phodam\Provider\ProviderContextInterface;
use MyLibrary\Model\User;

#[PhodamProvider(type: User::class)]
class UserProvider implements TypedProviderInterface
{
    /**
     * @implements TypedProviderInterface<User>
     */
    public function create(ProviderContextInterface $context): User
    {
        $overrides = $context->getOverrides();
        
        $user = new User();
        $user->setId($overrides['id'] ?? rand(1, 10000));
        $user->setName($overrides['name'] ?? 'John Doe');
        $user->setEmail($overrides['email'] ?? 'john.doe@example.com');
        
        return $user;
    }
}
```

### Named Provider Example

To create a named provider (allowing multiple providers for the same type), specify the `name` parameter:

```php
<?php

namespace MyLibrary\Provider;

use Phodam\Provider\PhodamProvider;
use Phodam\Provider\TypedProviderInterface;
use Phodam\Provider\ProviderContextInterface;
use MyLibrary\Model\Product;

#[PhodamProvider(type: Product::class, name: 'premium')]
class PremiumProductProvider implements TypedProviderInterface
{
    /**
     * @implements TypedProviderInterface<Product>
     */
    public function create(ProviderContextInterface $context): Product
    {
        $overrides = $context->getOverrides();
        
        $product = new Product();
        $product->setName($overrides['name'] ?? 'Premium Product');
        $product->setPrice($overrides['price'] ?? rand(10000, 100000) / 100);
        $product->setPremium(true);
        
        return $product;
    }
}
```

### Provider with Nested Objects

Providers can use the Phodam instance from the context to create nested objects:

```php
<?php

namespace MyLibrary\Provider;

use Phodam\Provider\PhodamProvider;
use Phodam\Provider\TypedProviderInterface;
use Phodam\Provider\ProviderContextInterface;
use MyLibrary\Model\Order;
use MyLibrary\Model\User;

#[PhodamProvider(type: Order::class)]
class OrderProvider implements TypedProviderInterface
{
    /**
     * @implements TypedProviderInterface<Order>
     */
    public function create(ProviderContextInterface $context): Order
    {
        $overrides = $context->getOverrides();
        $phodam = $context->getPhodam();
        
        $order = new Order();
        $order->setId($overrides['id'] ?? rand(1, 10000));
        $order->setUser($overrides['user'] ?? $phodam->create(User::class));
        $order->setTotal($overrides['total'] ?? rand(1000, 100000) / 100);
        
        return $order;
    }
}
```

### Array Provider Example

Array providers must be named and use the `PhodamArrayProvider` attribute:

```php
<?php

namespace MyLibrary\Provider;

use Phodam\Provider\PhodamArrayProvider;
use Phodam\Provider\ProviderInterface;
use Phodam\Provider\ProviderContextInterface;

#[PhodamArrayProvider(name: 'shoppingCart')]
class ShoppingCartProvider implements ProviderInterface
{
    public function create(ProviderContextInterface $context): array
    {
        $overrides = $context->getOverrides();
        
        return [
            'id' => $overrides['id'] ?? rand(1, 10000),
            'items' => $overrides['items'] ?? [],
            'total' => $overrides['total'] ?? 0.0,
            'created_at' => $overrides['created_at'] ?? date('Y-m-d H:i:s'),
        ];
    }
}
```

## Creating a Provider Bundle

A provider bundle groups multiple providers together. Create a class that implements `ProviderBundleInterface`:

```php
<?php

namespace MyLibrary\Provider;

use Phodam\Provider\ProviderBundleInterface;
use Phodam\Types\TypeDefinition;

class MyLibraryProviderBundle implements ProviderBundleInterface
{
    public function getProviders(): array
    {
        return [
            UserProvider::class,
            OrderProvider::class,
            ProductProvider::class,
            PremiumProductProvider::class,
            ShoppingCartProvider::class,
        ];
    }

    public function getTypeDefinitions(): array
    {
        // Return any type definitions you want to register
        return [];
    }
}
```

### How It Works

When you register a bundle, Phodam will:
1. Get the list of provider class names from `getProviders()`
2. Scan each class for `PhodamProvider` or `PhodamArrayProvider` attributes
3. Register each provider based on the type and name specified in the attributes

### Override Providers

You can mark providers as override providers using the `overriding` parameter in the attribute. This allows a provider to override an existing provider for the same type:

```php
#[PhodamProvider(type: User::class, overriding: true)]
class CustomUserProvider implements TypedProviderInterface
{
    // This provider will override any existing User provider
}
```

## Registering a Bundle in Phodam

Once you've created your provider bundle, you need to register it with Phodam. This is done through the `PhodamSchema` interface.

### Basic Registration

```php
<?php

use Phodam\PhodamSchema;
use MyLibrary\Provider\MyLibraryProviderBundle;

// Create a schema instance
$schema = PhodamSchema::withDefaults();
$phodam = $schema->getPhodam();

// Register your bundle (using class name)
$schema->registerBundle(MyLibraryProviderBundle::class);
```

### Using the Registered Providers

After registering the bundle, you can use all the providers it contains:

```php
// Create instances using the registered providers
$user = $phodam->create(User::class);
$order = $phodam->create(Order::class);

// Use named providers
$premiumProduct = $phodam->create(Product::class, name: 'premium');

// Use array providers
$cart = $phodam->createArray('shoppingCart');

// With overrides
$customUser = $phodam->create(User::class, overrides: ['name' => 'Jane Doe']);
```

### Registering Multiple Bundles

You can register multiple bundles:

```php
$schema->registerBundle(MyLibraryProviderBundle::class);
$schema->registerBundle(AnotherProviderBundle::class);
$schema->registerBundle(ThirdProviderBundle::class);
```

### Registering Individual Providers

If you need to register a provider without creating a bundle, you can register it directly:

```php
// Register a single provider
$schema->registerProvider(UserProvider::class);
```

## Complete Example

Here's a complete example of creating a provider library with a bundle:

### Directory Structure

```
my-library/
├── src/
│   ├── Model/
│   │   ├── User.php
│   │   └── Order.php
│   └── Provider/
│       ├── UserProvider.php
│       ├── OrderProvider.php
│       └── MyLibraryProviderBundle.php
├── composer.json
└── README.md
```

### Model Classes

**src/Model/User.php:**
```php
<?php



namespace MyLibrary\Model;

class User
{
    private int $id;
    private string $name;
    private string $email;
    
    // Getters and setters...
}
```

**src/Model/Order.php:**
```php
<?php



namespace MyLibrary\Model;

class Order
{
    private int $id;
    private User $user;
    private float $total;
    
    // Getters and setters...
}
```

### Providers

**src/Provider/UserProvider.php:**
```php
<?php

namespace MyLibrary\Provider;

use Phodam\Provider\PhodamProvider;
use Phodam\Provider\TypedProviderInterface;
use Phodam\Provider\ProviderContextInterface;
use MyLibrary\Model\User;

#[PhodamProvider(type: User::class)]
class UserProvider implements TypedProviderInterface
{
    /**
     * @implements TypedProviderInterface<User>
     */
    public function create(ProviderContextInterface $context): User
    {
        $overrides = $context->getOverrides();
        
        $user = new User();
        $user->setId($overrides['id'] ?? rand(1, 10000));
        $user->setName($overrides['name'] ?? 'John Doe');
        $user->setEmail($overrides['email'] ?? 'john.doe@example.com');
        
        return $user;
    }
}
```

**src/Provider/OrderProvider.php:**
```php
<?php

namespace MyLibrary\Provider;

use Phodam\Provider\PhodamProvider;
use Phodam\Provider\TypedProviderInterface;
use Phodam\Provider\ProviderContextInterface;
use MyLibrary\Model\Order;
use MyLibrary\Model\User;

#[PhodamProvider(type: Order::class)]
class OrderProvider implements TypedProviderInterface
{
    /**
     * @implements TypedProviderInterface<Order>
     */
    public function create(ProviderContextInterface $context): Order
    {
        $overrides = $context->getOverrides();
        $phodam = $context->getPhodam();
        
        $order = new Order();
        $order->setId($overrides['id'] ?? rand(1, 10000));
        $order->setUser($overrides['user'] ?? $phodam->create(User::class));
        $order->setTotal($overrides['total'] ?? rand(1000, 100000) / 100);
        
        return $order;
    }
}
```

### Provider Bundle

**src/Provider/MyLibraryProviderBundle.php:**
```php
<?php

namespace MyLibrary\Provider;

use Phodam\Provider\ProviderBundleInterface;
use Phodam\Types\TypeDefinition;

class MyLibraryProviderBundle implements ProviderBundleInterface
{
    public function getProviders(): array
    {
        return [
            UserProvider::class,
            OrderProvider::class,
        ];
    }

    public function getTypeDefinitions(): array
    {
        return [];
    }
}
```

### Composer Configuration

**composer.json:**
```json
{
    "name": "my-library/phodam-providers",
    "description": "Phodam providers for MyLibrary models",
    "type": "library",
    "require": {
        "php": "^8.4",
        "phodam/phodam-api": "^2.0"
    },
    "autoload": {
        "psr-4": {
            "MyLibrary\\": "src/"
        }
    }
}
```

### Usage in Your Application

Once your library is installed, users can register it like this:

```php
<?php

use Phodam\PhodamSchema;
use MyLibrary\Provider\MyLibraryProviderBundle;
use MyLibrary\Model\User;
use MyLibrary\Model\Order;

// Create schema and get Phodam instance
$schema = PhodamSchema::withDefaults();
$phodam = $schema->getPhodam();

// Register the bundle - all providers are now available
$schema->registerBundle(MyLibraryProviderBundle::class);

// Use the providers
$user = $phodam->create(User::class);
$order = $phodam->create(Order::class);

// With overrides
$customUser = $phodam->create(User::class, overrides: ['name' => 'Jane Doe']);
```

## Advanced Topics

### Type Definitions

You can register type definitions alongside providers in your bundle:

```php
use Phodam\Types\TypeDefinition;
use Phodam\Types\FieldDefinition;

class MyProviderBundle implements ProviderBundleInterface
{
    public function getProviders(): array
    {
        return [];
    }

    public function getTypeDefinitions(): array
    {
        $articleDefinition = new TypeDefinition(
            Article::class,
            fields: [
                'id' => new FieldDefinition('int'),
                'name' => new FieldDefinition('string', nullable: true),
                'tags' => new FieldDefinition('string', array: true)
            ]
        );
        
        return [$articleDefinition];
    }
}
```

### Handling Overrides

Always check for overrides in your providers:

```php
public function create(ProviderContextInterface $context): MyType
{
    $overrides = $context->getOverrides();
    
    // Check if a specific field is overridden
    if ($context->hasOverride('fieldName')) {
        $value = $context->getOverride('fieldName');
    } else {
        $value = $this->generateDefaultValue();
    }
}
```

### Using Configuration

Providers can accept configuration through the context:

```php
public function create(ProviderContextInterface $context): MyType
{
    $config = $context->getConfig();
    $minValue = $config['min'] ?? 0;
    $maxValue = $config['max'] ?? 100;
    
    // Use config values...
}
```

Usage:
```php
$phodam->create(MyType::class, overrides: ['min' => 10, 'max' => 50]);
```

### Error Handling

Providers can throw exceptions if they cannot create a value:

```php
use Phodam\Provider\CreationFailedException;

public function create(ProviderContextInterface $context): MyType
{
    if ($someCondition) {
        throw new CreationFailedException('Unable to create MyType: reason');
    }
    
    // Create and return...
}
```

## Best Practices

1. **Use TypedProviderInterface** - Provides better type safety and IDE support
2. **Handle Overrides** - Always check for and respect override values
3. **Use Meaningful Defaults** - Generate realistic test data
4. **Document Your Providers** - Add PHPDoc comments explaining what each provider creates
5. **Keep Providers Focused** - Each provider should handle one type
6. **Use Named Providers** - When you need multiple variations of the same type
7. **Leverage Phodam** - Use `$context->getPhodam()` to create nested objects
8. **Test Your Providers** - Write unit tests for your providers
9. **Bundle Related Providers** - Group providers that belong together in a single bundle

## Summary

Creating and registering a provider bundle involves:

1. **Create individual providers** - Implement `ProviderInterface` or `TypedProviderInterface` with appropriate attributes
2. **Create a bundle class** - Implement `ProviderBundleInterface` and return all provider class names in `getProviders()`
3. **Register the bundle** - Call `$schema->registerBundle(YourBundle::class)` to register all providers at once

This approach makes it easy to distribute provider libraries and allows users to register your entire library with a single call.
