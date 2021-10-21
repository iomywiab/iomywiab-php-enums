<?php

/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: EnumValueInterface.php
 * Class name...: EnumValueInterface.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-21 08:08:25
 */
declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums\interfaces;

use Serializable;

/**
 * Interface EnumValueInterface
 * @package iomywiab\iomywiab_php_enums\interfaces
 * @property-read EnumDefinitionInterface $definition
 * @property-read int                     $ordinal
 * @property-read string                  $name
 */
interface EnumValueInterface extends Serializable
{

    /**
     * Returns the definition of the enumeration
     * @return EnumDefinitionInterface
     */
    public function getDefinition(): EnumDefinitionInterface;

    /**
     * Returns the int representation of the current value of the enumerator.
     * @return int
     */
    public function getOrdinal(): int;

    /**
     * Returns the string representation of the current value of the enumerator.
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function toDisplay(): string;

}