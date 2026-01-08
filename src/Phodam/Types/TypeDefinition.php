<?php

// This file is part of Phodam
// Copyright (c) Andrew Vehlies <avehlies@gmail.com>
// Licensed under the MIT license. See LICENSE file in the project root.
// SPDX-License-Identifier: MIT

namespace Phodam\Types;

use Exception;

/**
 * @template T
 */
class TypeDefinition
{
    /** @var class-string<T> */
    private string $type;
    private ?string $name = null;
    private bool $overriding = false;
    /** @var array<string, FieldDefinition<*>> */
    private array $fields = [];

    /**
     * @param class-string<T> $type
     * @param string|null $name
     * @param bool $overriding
     * @param array<string, FieldDefinition<*>> $fields
     */
    public function __construct(string $type, ?string $name = null, bool $overriding = false, array $fields = [])
    {
        $this->type = $type;
        $this->name = $name;
        $this->overriding = $overriding;
        $this->fields = $fields;
    }

    /**
     * @return class-string<T>
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isOverriding(): bool
    {
        return $this->overriding;
    }

    /**
     * @return array<string, FieldDefinition<*>>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param string $name
     * @param FieldDefinition<*> $definition
     * @return $this
     */
    public function addFieldDefinition(string $name, FieldDefinition $definition): self
    {
        $this->fields[$name] = $definition;
        return $this;
    }

    /**
     * @param string $name
     * @param string|class-string<T> $type
     * @param array<string, mixed>|null $config
     * @param array<string, mixed>|null $overrides
     * @param bool $nullable
     * @param bool $array
     * @return $this
     */
    public function addField(
        string $name,
        string $type,
        ?array $config = [],
        ?array $overrides = [],
        bool $nullable = false,
        bool $array = false
    ): self {
        $this->addFieldDefinition(
            $name,
            definition: new FieldDefinition(
                type: $type,
                name: $name,
                config: $config,
                overrides: $overrides,
                nullable: $nullable,
                array: $array
            )
        );
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getFieldNames(): array
    {
        return array_keys($this->fields);
    }

    /**
     * @param string $name
     * @return FieldDefinition<*>
     * @throws Exception
     */
    public function getField(string $name): FieldDefinition
    {
        if (!array_key_exists($name, $this->fields)) {
            throw new Exception('Unable to find field by name: ' . $name);
        }
        return $this->fields[$name];
    }
}
