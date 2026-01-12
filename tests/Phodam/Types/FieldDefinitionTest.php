<?php

// This file is part of Phodam
// Copyright (c) Andrew Vehlies <avehlies@gmail.com>
// Licensed under the MIT license. See LICENSE file in the project root.
// SPDX-License-Identifier: MIT

namespace PhodamTests\Phodam\Types;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Phodam\Types\FieldDefinition;
use PhodamTests\Fixtures\SimpleType;
use PhodamTests\Phodam\PhodamBaseTestCase;

#[CoversClass(FieldDefinition::class)]
#[CoversFunction(FieldDefinition::class . '::__construct')]
#[CoversFunction(FieldDefinition::class . '::getType')]
#[CoversFunction(FieldDefinition::class . '::getName')]
#[CoversFunction(FieldDefinition::class . '::getConfig')]
#[CoversFunction(FieldDefinition::class . '::getOverrides')]
#[CoversFunction(FieldDefinition::class . '::isNullable')]
#[CoversFunction(FieldDefinition::class . '::isArray')]
class FieldDefinitionTest extends PhodamBaseTestCase
{
    #[Test]
    #[TestDox('Default constructor creates field definition')]
    public function testDefaultConstructor(): void
    {
        $type = SimpleType::class;
        $def = new FieldDefinition($type);

        $this->assertEquals($type, $def->getType());
        $this->assertNull($def->getName());
        $this->assertIsArray($def->getConfig());
        $this->assertEmpty($def->getConfig());
        $this->assertIsArray($def->getOverrides());
        $this->assertEmpty($def->getOverrides());
        $this->assertFalse($def->isNullable());
        $this->assertFalse($def->isArray());
    }

    #[Test]
    #[TestDox('Getters work as expected')]
    public function testGetters(): void
    {
        $type = SimpleType::class;
        $name = 'MyName';
        $overrides = ['a' => 'b'];
        $config = ['c' => 'd'];
        $nullable = true;
        $array = true;
        $def = new FieldDefinition(
            type: $type,
            name: $name,
            config: $config,
            overrides: $overrides,
            nullable: $nullable,
            array: $array
        );

        $this->assertInstanceOf(FieldDefinition::class, $def);
        $this->assertEquals($type, $def->getType());
        $this->assertEquals($name, $def->getName());
        $this->assertIsArray($def->getOverrides());
        $this->assertEquals($type, $def->getType());
        $this->assertIsArray($def->getConfig());
        $this->assertTrue($def->isNullable());
        $this->assertTrue($def->isArray());
    }

    #[Test]
    #[TestDox('Constructor with null config sets config to null')]
    public function testConstructorWithNullConfig(): void
    {
        $type = SimpleType::class;
        $def = new FieldDefinition($type, config: null);

        $this->assertEquals($type, $def->getType());
        $this->assertNull($def->getConfig());
    }

    #[Test]
    #[TestDox('getType returns the type')]
    public function testGetType(): void
    {
        $type = SimpleType::class;
        $def = new FieldDefinition($type);

        $this->assertEquals($type, $def->getType());
    }

    #[Test]
    #[TestDox('getName returns the name when provided')]
    public function testGetName(): void
    {
        $name = 'MyField';
        $def = new FieldDefinition(SimpleType::class, name: $name);

        $this->assertEquals($name, $def->getName());
    }

    #[Test]
    #[TestDox('getName returns null when name is not provided')]
    public function testGetNameWithNull(): void
    {
        $def = new FieldDefinition(SimpleType::class);

        $this->assertNull($def->getName());
    }

    #[Test]
    #[TestDox('getConfig returns config when provided')]
    public function testGetConfig(): void
    {
        $config = ['key' => 'value'];
        $def = new FieldDefinition(SimpleType::class, config: $config);

        $this->assertEquals($config, $def->getConfig());
    }

    #[Test]
    #[TestDox('getConfig returns empty array by default')]
    public function testGetConfigDefault(): void
    {
        $def = new FieldDefinition(SimpleType::class);

        $this->assertIsArray($def->getConfig());
        $this->assertEmpty($def->getConfig());
    }

    #[Test]
    #[TestDox('getConfig returns null when set to null')]
    public function testGetConfigWithNull(): void
    {
        $def = new FieldDefinition(SimpleType::class, config: null);

        $this->assertNull($def->getConfig());
    }

    #[Test]
    #[TestDox('getOverrides returns overrides when provided')]
    public function testGetOverrides(): void
    {
        $overrides = ['key' => 'value'];
        $def = new FieldDefinition(SimpleType::class, overrides: $overrides);

        $this->assertEquals($overrides, $def->getOverrides());
    }

    #[Test]
    #[TestDox('getOverrides returns empty array by default')]
    public function testGetOverridesDefault(): void
    {
        $def = new FieldDefinition(SimpleType::class);

        $this->assertIsArray($def->getOverrides());
        $this->assertEmpty($def->getOverrides());
    }

    #[Test]
    #[TestDox('isNullable returns true when nullable is true')]
    public function testIsNullableReturnsTrue(): void
    {
        $def = new FieldDefinition(SimpleType::class, nullable: true);

        $this->assertTrue($def->isNullable());
    }

    #[Test]
    #[TestDox('isNullable returns false when nullable is false')]
    public function testIsNullableReturnsFalse(): void
    {
        $def = new FieldDefinition(SimpleType::class, nullable: false);

        $this->assertFalse($def->isNullable());
    }

    #[Test]
    #[TestDox('isNullable returns false by default')]
    public function testIsNullableDefault(): void
    {
        $def = new FieldDefinition(SimpleType::class);

        $this->assertFalse($def->isNullable());
    }

    #[Test]
    #[TestDox('isArray returns true when array is true')]
    public function testIsArrayReturnsTrue(): void
    {
        $def = new FieldDefinition(SimpleType::class, array: true);

        $this->assertTrue($def->isArray());
    }

    #[Test]
    #[TestDox('isArray returns false when array is false')]
    public function testIsArrayReturnsFalse(): void
    {
        $def = new FieldDefinition(SimpleType::class, array: false);

        $this->assertFalse($def->isArray());
    }

    #[Test]
    #[TestDox('isArray returns false by default')]
    public function testIsArrayDefault(): void
    {
        $def = new FieldDefinition(SimpleType::class);

        $this->assertFalse($def->isArray());
    }

}
