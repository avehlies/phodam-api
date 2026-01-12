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
use Phodam\Types\TypeDefinition;
use PhodamTests\Fixtures\SimpleType;
use PhodamTests\Phodam\PhodamBaseTestCase;

#[CoversClass(TypeDefinition::class)]
#[CoversFunction(TypeDefinition::class . '::__construct')]
#[CoversFunction(TypeDefinition::class . '::getType')]
#[CoversFunction(TypeDefinition::class . '::getName')]
#[CoversFunction(TypeDefinition::class . '::isOverriding')]
#[CoversFunction(TypeDefinition::class . '::getField')]
#[CoversFunction(TypeDefinition::class . '::getFields')]
#[CoversFunction(TypeDefinition::class . '::addField')]
#[CoversFunction(TypeDefinition::class . '::addFieldDefinition')]
#[CoversFunction(TypeDefinition::class . '::getFieldNames')]
class TypeDefinitionTest extends PhodamBaseTestCase
{
    #[Test]
    #[TestDox('Constructor with type only creates definition with defaults')]
    public function testConstructorWithTypeOnly(): void
    {
        $type = SimpleType::class;
        $def = new TypeDefinition($type);

        $this->assertEquals($type, $def->getType());
        $this->assertNull($def->getName());
        $this->assertFalse($def->isOverriding());
        $this->assertIsArray($def->getFields());
        $this->assertEmpty($def->getFields());
        $this->assertIsArray($def->getFieldNames());
        $this->assertEmpty($def->getFieldNames());
    }

    #[Test]
    #[TestDox('Constructor with type and name sets type and name')]
    public function testConstructorWithTypeAndName(): void
    {
        $type = SimpleType::class;
        $name = 'MyProvider';
        $def = new TypeDefinition($type, name: $name);

        $this->assertEquals($type, $def->getType());
        $this->assertEquals($name, $def->getName());
        $this->assertFalse($def->isOverriding());
        $this->assertIsArray($def->getFields());
        $this->assertEmpty($def->getFields());
    }

    #[Test]
    #[TestDox('Constructor with type, name, and overriding sets all properties')]
    public function testConstructorWithTypeNameAndOverriding(): void
    {
        $type = SimpleType::class;
        $name = 'MyProvider';
        $overriding = true;
        $def = new TypeDefinition($type, name: $name, overriding: $overriding);

        $this->assertEquals($type, $def->getType());
        $this->assertEquals($name, $def->getName());
        $this->assertTrue($def->isOverriding());
        $this->assertIsArray($def->getFields());
        $this->assertEmpty($def->getFields());
    }

    #[Test]
    #[TestDox('Constructor with all parameters sets all properties including fields')]
    public function testConstructorWithAllParameters(): void
    {
        $type = SimpleType::class;
        $name = 'MyProvider';
        $overriding = true;
        $field1 = new FieldDefinition(SimpleType::class);
        $field2 = new FieldDefinition(SimpleType::class);
        $fields = [
            'field1' => $field1,
            'field2' => $field2
        ];

        $def = new TypeDefinition($type, name: $name, overriding: $overriding, fields: $fields);

        $this->assertEquals($type, $def->getType());
        $this->assertEquals($name, $def->getName());
        $this->assertTrue($def->isOverriding());
        $this->assertIsArray($def->getFields());
        $this->assertCount(2, $def->getFields());
        $this->assertEquals($fields, $def->getFields());
        $this->assertSame($field1, $def->getFields()['field1']);
        $this->assertSame($field2, $def->getFields()['field2']);
    }

    #[Test]
    #[TestDox('getType returns the type')]
    public function testGetType(): void
    {
        $type = SimpleType::class;
        $def = new TypeDefinition($type);

        $this->assertEquals($type, $def->getType());
    }

    #[Test]
    #[TestDox('getName returns the name when provided')]
    public function testGetName(): void
    {
        $name = 'MyProvider';
        $def = new TypeDefinition(SimpleType::class, name: $name);

        $this->assertEquals($name, $def->getName());
    }

    #[Test]
    #[TestDox('getName returns null when name is not provided')]
    public function testGetNameWithNull(): void
    {
        $def = new TypeDefinition(SimpleType::class);

        $this->assertNull($def->getName());
    }

    #[Test]
    #[TestDox('isOverriding returns true when overriding is true')]
    public function testIsOverridingReturnsTrue(): void
    {
        $def = new TypeDefinition(SimpleType::class, overriding: true);

        $this->assertTrue($def->isOverriding());
    }

    #[Test]
    #[TestDox('isOverriding returns false when overriding is false')]
    public function testIsOverridingReturnsFalse(): void
    {
        $def = new TypeDefinition(SimpleType::class, overriding: false);

        $this->assertFalse($def->isOverriding());
    }

    #[Test]
    #[TestDox('isOverriding returns false by default')]
    public function testIsOverridingDefault(): void
    {
        $def = new TypeDefinition(SimpleType::class);

        $this->assertFalse($def->isOverriding());
    }


    #[Test]
    #[TestDox('addFieldDefinition adds field definitions')]
    public function testAddFieldDefinition(): void
    {
        $def = new TypeDefinition(SimpleType::class);

        $field1 = new FieldDefinition(SimpleType::class);
        $field2 = new FieldDefinition(SimpleType::class);

        $result1 = $def->addField('field1', definition: $field1);
        $result2 = $def->addField('field2', definition: $field2);

        $this->assertInstanceOf(TypeDefinition::class, $result1);
        $this->assertInstanceOf(TypeDefinition::class, $result2);
        $this->assertSame($def, $result1);
        $this->assertSame($def, $result2);
        $this->assertCount(2, $def->getFields());
        $this->assertSame($field1, $def->getFields()['field1']);
        $this->assertSame($field2, $def->getFields()['field2']);
    }

    #[Test]
    #[TestDox('addFieldDefinition replaces existing field with same name')]
    public function testAddFieldReplacesExistingField(): void
    {
        $def = new TypeDefinition(SimpleType::class);
        $field1 = new FieldDefinition(SimpleType::class);
        $field2 = new FieldDefinition(SimpleType::class);

        $def->addField('field1', definition: $field1);
        $def->addField('field1', definition: $field2);

        $this->assertCount(1, $def->getFields());
        $this->assertSame($field2, $def->getFields()['field1']);
        $this->assertNotSame($field1, $def->getFields()['field1']);
    }

    #[Test]
    #[TestDox('getFieldNames returns array of field names when fields are added')]
    public function testGetFieldNames(): void
    {
        $def = new TypeDefinition(SimpleType::class);

        $this->assertIsArray($def->getFieldNames());
        $this->assertEmpty($def->getFieldNames());

        $field1 = new FieldDefinition(SimpleType::class);
        $field2 = new FieldDefinition(SimpleType::class);
        $field3 = new FieldDefinition(SimpleType::class);

        $def->addField('field1', definition: $field1);
        $def->addField('field2', definition: $field2);
        $def->addField('field3', definition: $field3);

        $fieldNames = $def->getFieldNames();
        $this->assertIsArray($fieldNames);
        $this->assertCount(3, $fieldNames);
        $this->assertContains('field1', $fieldNames);
        $this->assertContains('field2', $fieldNames);
        $this->assertContains('field3', $fieldNames);
    }

    #[Test]
    #[TestDox('getFieldNames returns empty array when no fields are set')]
    public function testGetFieldNamesReturnsEmptyArray(): void
    {
        $def = new TypeDefinition(SimpleType::class);

        $fieldNames = $def->getFieldNames();
        $this->assertIsArray($fieldNames);
        $this->assertEmpty($fieldNames);
    }

    #[Test]
    #[TestDox('getField returns field when field exists')]
    public function testGetField(): void
    {
        $field1 = new FieldDefinition(SimpleType::class);
        $field2 = new FieldDefinition(SimpleType::class);
        $fields = [
            'field1' => $field1,
            'field2' => $field2
        ];

        $def = new TypeDefinition(SimpleType::class, null, false, $fields);

        $this->assertSame($field1, $def->getField('field1'));
        $this->assertSame($field2, $def->getField('field2'));
    }

    #[Test]
    #[TestDox('getField throws exception when field not found')]
    public function testGetFieldThrowsExceptionWhenFieldNotFound(): void
    {
        $def = new TypeDefinition(SimpleType::class);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unable to find field by name: nonexistent');

        $def->getField('nonexistent');
    }

    #[Test]
    #[TestDox('getField throws exception with correct message format')]
    public function testGetFieldThrowsExceptionWithCorrectMessage(): void
    {
        $def = new TypeDefinition(SimpleType::class);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unable to find field by name: missingField');

        $def->getField('missingField');
    }
}
