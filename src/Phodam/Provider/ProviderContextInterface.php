<?php

// This file is part of Phodam
// Copyright (c) Chris Bouchard <chris@upliftinglemma.net>
// Licensed under the MIT license. See LICENSE file in the project root.
// SPDX-License-Identifier: MIT

namespace Phodam\Provider;

use Phodam\PhodamInterface;

interface ProviderContextInterface
{
    /**
     * Return the type to be created by the provider.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Return an array of overrides for specific fields for the value created by
     * the provider.
     *
     * @return array<string, mixed>
     */
    public function getOverrides(): array;

    /**
     * Return whether the given field is overridden in this context.
     *
     * @param string $field the field name to check
     * @return bool whether the given field is overridden
     */
    public function hasOverride(string $field): bool;

    /**
     * Return the override value for the given field in this context.
     *
     * @param string $field the field name to check
     * @return mixed the overridden value
     */
    public function getOverride(string $field);

    /**
     * Return provider-specific information. An open-ended array for the
     * provider to pass information along.
     *
     * @return array<string, mixed>
     */
    public function getConfig(): array;

    /**
     * Return the Phodam instance.
     *
     * @return PhodamInterface
     */
    public function getPhodam(): PhodamInterface;
}
