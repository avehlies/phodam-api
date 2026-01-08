<?php

// This file is part of Phodam
// Copyright (c) Andrew Vehlies <avehlies@gmail.com>
// Licensed under the MIT license. See LICENSE file in the project root.
// SPDX-License-Identifier: MIT

namespace PhodamTests\Phodam;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Phodam\PhodamException;
use Phodam\Provider\CreationFailedException;
use Phodam\Store\ProviderNotFoundException;

#[CoversClass(PhodamException::class)]
#[CoversFunction(PhodamException::class . '::__construct')]
class PhodamExceptionTest extends PhodamBaseTestCase
{
    #[Test]
    #[TestDox('PhodamException is an abstract class that extends Exception')]
    public function testPhodamExceptionIsAbstract(): void
    {
        $reflection = new \ReflectionClass(PhodamException::class);
        $this->assertTrue($reflection->isAbstract());
        $this->assertTrue($reflection->isSubclassOf(\Exception::class));
    }

    #[Test]
    #[TestDox('CreationFailedException extends PhodamException')]
    public function testCreationFailedExceptionExtendsPhodamException(): void
    {
        $exception = new CreationFailedException('MyType', null);
        $this->assertInstanceOf(PhodamException::class, $exception);
    }

    #[Test]
    #[TestDox('ProviderNotFoundException extends PhodamException')]
    public function testProviderNotFoundExceptionExtendsPhodamException(): void
    {
        $exception = new ProviderNotFoundException();
        $this->assertInstanceOf(PhodamException::class, $exception);
    }

    #[Test]
    #[TestDox('PhodamException can be instantiated through concrete implementations')]
    public function testPhodamExceptionCanBeInstantiated(): void
    {
        $exception1 = new CreationFailedException('MyType', null);
        $exception2 = new ProviderNotFoundException();

        $this->assertInstanceOf(PhodamException::class, $exception1);
        $this->assertInstanceOf(PhodamException::class, $exception2);
    }
}
