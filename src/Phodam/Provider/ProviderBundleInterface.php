<?php

// This file is part of Phodam
// Copyright (c) Chris Bouchard <chris@upliftinglemma.net>
// Licensed under the MIT license. See LICENSE file in the project root.
// SPDX-License-Identifier: MIT

declare(strict_types=1);

namespace Phodam\Provider;

use Phodam\Provider\ProviderInterface;
use Phodam\Types\TypeDefinition;

interface ProviderBundleInterface
{
    /**
     * Returns an array of provider class names that should be registered.
     * These classes will be scanned for PhodamProvider/PhodamArrayProvider attributes.
     * 
     * @return array<class-string<ProviderInterface>>
     */
    public function getProviders(): array;

    /**
     * Returns an array of type definitions that should be registered.
     * 
     * @return array<TypeDefinition>
     */
    public function getTypeDefinitions(): array;
}
