<?php

// This file is part of Phodam
// Copyright (c) Andrew Vehlies <avehlies@gmail.com>
// Copyright (c) Chris Bouchard <chris@upliftinglemma.net>
// Licensed under the MIT license. See LICENSE file in the project root.
// SPDX-License-Identifier: MIT

namespace Phodam\Provider;

/**
 * @template T
 */
interface TypedProviderInterface extends ProviderInterface
{
    /**
     * @inheritDoc
     * @return T
     */
    public function create(ProviderContextInterface $context);
}
