<?php

// This file is part of Phodam
// Copyright (c) Chris Bouchard <chris@upliftinglemma.net>
// Licensed under the MIT license. See LICENSE file in the project root.
// SPDX-License-Identifier: MIT

declare(strict_types=1);

namespace Phodam;

use Phodam\Provider\ProviderBundleInterface;
use Phodam\Provider\ProviderInterface;
use Phodam\Types\TypeDefinition;

interface PhodamSchemaInterface
{
    /**
     * @param ProviderBundleInterface | class-string<ProviderBundleInterface> $bundleOrClass
     */
    public function registerBundle($bundleOrClass): void;

    /**
     * @param ProviderInterface | class-string<ProviderInterface> $providerOrClass
     */
    public function registerProvider($providerOrClass): void;

    /**
     * @param TypeDefinition $definition
     */
    public function registerTypeDefinition(TypeDefinition $definition): void;

    public function getPhodam(): PhodamInterface;
}
