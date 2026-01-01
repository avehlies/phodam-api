<?php

// This file is part of Phodam
// Copyright (c) Chris Bouchard <chris@upliftinglemma.net>
// Licensed under the MIT license. See LICENSE file in the project root.
// SPDX-License-Identifier: MIT

declare(strict_types=1);

namespace Phodam;

use Phodam\Provider\ProviderBundleInterface;
use Phodam\Store\RegistrarInterface;

interface PhodamSchemaInterface
{
    public function forType(string $type): RegistrarInterface;

    /**
     * @param ProviderBundleInterface | class-string<ProviderBundleInterface> $bundle
     */
    public function add($bundle): void;

    public function getPhodam(): PhodamInterface;
}
