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
    private ?string $name;
    private bool $overriding;
    /** @var array<string, FieldDefinition> */
    private array $fields;
    /** @var array<string, mixed> */
    private array $overrides;

    /**
     * @param array<string, FieldDefinition> $fields
     */
    public function __construct(
        array $fields = []
    ) {
        $this->fields = $fields;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOverrides(): array
    {
        return $this->overrides;
    }

    /**
     * @param array<string, mixed> $overrides
     * @return $this
     */
    public function setOverrides(array $overrides): self
    {
        $this->overrides = $overrides;
        return $this;
    }

    public function isOverriding(): bool {
        return $this->overriding;
    }

    public function setOverriding(bool $overriding): self {
        $this->overriding = $overriding;
        return $this;
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
