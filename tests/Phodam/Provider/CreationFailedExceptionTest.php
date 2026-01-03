<?php

// This file is part of Phodam
// Copyright (c) Andrew Vehlies <avehlies@gmail.com>
// Licensed under the MIT license. See LICENSE file in the project root.
// SPDX-License-Identifier: MIT

declare(strict_types=1);

namespace PhodamTests\Phodam\Provider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Phodam\Provider\CreationFailedException;
use PhodamTests\Phodam\PhodamBaseTestCase;
use Throwable;

#[CoversClass(CreationFailedException::class)]
#[CoversFunction(CreationFailedException::class . '::__construct')]
#[CoversFunction(CreationFailedException::class . '::getType')]
#[CoversFunction(CreationFailedException::class . '::getName')]
class CreationFailedExceptionTest extends PhodamBaseTestCase
{
    #[Test]
    #[TestDox('Constructor with type and name creates exception with default message')]
    public function testConstructorWithTypeAndName(): void
    {
        $type = 'MyType';
        $name = 'MyProvider';
        $exception = new CreationFailedException($type, $name);

        $this->assertInstanceOf(CreationFailedException::class, $exception);
        $this->assertEquals($type, $exception->getType());
        $this->assertEquals($name, $exception->getName());
        $this->assertEquals(
            "Creation failed for type {$type} using provider named {$name}",
            $exception->getMessage()
        );
    }

    #[Test]
    #[TestDox('Constructor with type and null name creates exception with default message')]
    public function testConstructorWithTypeAndNullName(): void
    {
        $type = 'MyType';
        $exception = new CreationFailedException($type, null);

        $this->assertInstanceOf(CreationFailedException::class, $exception);
        $this->assertEquals($type, $exception->getType());
        $this->assertNull($exception->getName());
        $this->assertEquals(
            "Creation failed for type {$type} using default provider",
            $exception->getMessage()
        );
    }

    #[Test]
    #[TestDox('Constructor with custom message uses custom message')]
    public function testConstructorWithCustomMessage(): void
    {
        $type = 'MyType';
        $name = 'MyProvider';
        $customMessage = 'Custom error message';
        $exception = new CreationFailedException($type, $name, $customMessage);

        $this->assertInstanceOf(CreationFailedException::class, $exception);
        $this->assertEquals($type, $exception->getType());
        $this->assertEquals($name, $exception->getName());
        $this->assertEquals($customMessage, $exception->getMessage());
    }

    #[Test]
    #[TestDox('Constructor with previous exception preserves previous exception')]
    public function testConstructorWithPreviousException(): void
    {
        $type = 'MyType';
        $name = 'MyProvider';
        $previous = new \RuntimeException('Previous error');
        $exception = new CreationFailedException($type, $name, null, $previous);

        $this->assertInstanceOf(CreationFailedException::class, $exception);
        $this->assertEquals($type, $exception->getType());
        $this->assertEquals($name, $exception->getName());
        $this->assertSame($previous, $exception->getPrevious());
    }

    #[Test]
    #[TestDox('Get type returns the type')]
    public function testGetType(): void
    {
        $type = 'MyType';
        $exception = new CreationFailedException($type, null);

        $this->assertEquals($type, $exception->getType());
    }

    #[Test]
    #[TestDox('Get name returns the name when provided')]
    public function testGetNameWithName(): void
    {
        $name = 'MyProvider';
        $exception = new CreationFailedException('MyType', $name);

        $this->assertEquals($name, $exception->getName());
    }

    #[Test]
    #[TestDox('Get name returns null when name is not provided')]
    public function testGetNameWithNull(): void
    {
        $exception = new CreationFailedException('MyType', null);

        $this->assertNull($exception->getName());
    }
}
