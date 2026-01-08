<?php

// This file is part of Phodam
// Copyright (c) Andrew Vehlies <avehlies@gmail.com>
// Licensed under the MIT license. See LICENSE file in the project root.
// SPDX-License-Identifier: MIT

namespace Phodam\Types;

/**
 * @template T
 */
class FieldDefinition
{
    /** @var string|class-string<T> */
    private string $type;
    private ?string $name = null;
    /** @var array<string, mixed>|null $config */
    private ?array $config = [];
    /** @var array<string, mixed>|null $overrides */
    private ?array $overrides = [];
    private bool $nullable = false;
    private bool $array = false;

    /**
     * @param string|class-string<T> $type
     * @param string|null $name
     * @param array<string, mixed>|null $config
     * @param array<string, mixed>|null $overrides
     * @param bool $nullable
     * @param bool $array
     */
    public function __construct(
        string $type,
        ?string $name = null,
        ?array $config = [],
        ?array $overrides = [],
        bool $nullable = false,
        bool $array = false
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->config = $config;
        $this->overrides = $overrides;
        $this->nullable = $nullable;
        $this->array = $array;
    }

    /**
     * @return string|class-string<T>
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * @return array<string, mixed>|null
     */
    public function getConfig(): ?array
    {
        return $this->config;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getOverrides(): ?array
    {
        return $this->overrides;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return bool
     */
    public function isArray(): bool
    {
        return $this->array;
    }
}
