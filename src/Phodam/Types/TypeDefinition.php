<?php

// This file is part of Phodam
// Copyright (c) Andrew Vehlies <avehlies@gmail.com>
// Licensed under the MIT license. See LICENSE file in the project root.
// SPDX-License-Identifier: MIT

declare(strict_types=1);

namespace Phodam\Types;

use Exception;

class TypeDefinition
{
    private string $type;
    private ?string $name = null;
    private bool $overriding = false;
    /** @var array<string, FieldDefinition> */
    private array $fields = [];

    /**
     * @param string $type
     * @param string|null $name
     * @param bool $overriding
     * @param array<string, FieldDefinition> $fields
     */
    public function __construct(string $type, ?string $name = null, bool $overriding = false, array $fields = [])
    {
        $this->type = $type;
        $this->name = $name;
        $this->overriding = $overriding;
        $this->fields = $fields;
    }

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
     * @return array<string, FieldDefinition>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array<string, FieldDefinition> $fields
     * @return $this
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param string $name
     * @param FieldDefinition $definition
     * @return $this
     */
    public function addField(string $name, FieldDefinition $definition): self
    {
        $this->fields[$name] = $definition;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getFieldNames(): array
    {
        return array_keys($this->fields);
    }

    public function getField(string $name): FieldDefinition
    {
        if (!array_key_exists($name, $this->fields)) {
            throw new Exception('Unable to find field by name: ' . $name);
        }
        return $this->fields[$name];
    }
}
