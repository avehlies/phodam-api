<?php

// This file is part of Phodam
// Copyright (c) Andrew Vehlies <avehlies@gmail.com>
// Licensed under the MIT license. See LICENSE file in the project root.
// SPDX-License-Identifier: MIT

declare(strict_types=1);

namespace PhodamTests\Phodam\Store;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Phodam\Store\ProviderNotFoundException;
use PhodamTests\Phodam\PhodamBaseTestCase;

#[CoversClass(ProviderNotFoundException::class)]
#[CoversFunction(ProviderNotFoundException::class . '::__construct')]
class ProviderNotFoundExceptionTest extends PhodamBaseTestCase
{
    #[Test]
    #[TestDox('Exception can be instantiated with default constructor')]
    public function testDefaultConstructor(): void
    {
        $exception = new ProviderNotFoundException();

        $this->assertInstanceOf(ProviderNotFoundException::class, $exception);
        $this->assertInstanceOf(\Phodam\PhodamException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    #[Test]
    #[TestDox('Exception can be instantiated with message')]
    public function testConstructorWithMessage(): void
    {
        $message = 'Provider not found';
        $exception = new ProviderNotFoundException($message);

        $this->assertInstanceOf(ProviderNotFoundException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
    }

    #[Test]
    #[TestDox('Exception can be instantiated with message and code')]
    public function testConstructorWithMessageAndCode(): void
    {
        $message = 'Provider not found';
        $code = 404;
        $exception = new ProviderNotFoundException($message, $code);

        $this->assertInstanceOf(ProviderNotFoundException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }

    #[Test]
    #[TestDox('Exception can be instantiated with message, code, and previous exception')]
    public function testConstructorWithPreviousException(): void
    {
        $message = 'Provider not found';
        $code = 404;
        $previous = new \RuntimeException('Previous error');
        $exception = new ProviderNotFoundException($message, $code, $previous);

        $this->assertInstanceOf(ProviderNotFoundException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
