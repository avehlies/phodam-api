<?php

// This file is part of Phodam
// Copyright (c) Andrew Vehlies <avehlies@gmail.com>
// Licensed under the MIT license. See LICENSE file in the project root.
// SPDX-License-Identifier: MIT

namespace Phodam\Provider;

use Attribute;

/**
 * Attribute to declare an array provider.
 *
 * Array providers must be named. Place this attribute on an array provider class
 * to declare its name and optional override flag.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class PhodamArrayProvider
{
    public function __construct(
        public string $name,
        public bool $overriding = false
    ) {
    }
}
