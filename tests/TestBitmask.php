<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: TestBitmask.php
 * Class name...: TestBitmask.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-20 20:41:20
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums_tests;

use iomywiab\iomywiab_php_enums\EnumValue;

/**
 * Class ExampleValue
 * @package iomywiab\iomywiab_php_enums_tests
 */
class TestBitmask extends EnumValue
{
    public const ONE = 1;
    public const TWO = self::ONE << 1;
    public const THREE = self::TWO << 1;
    public const FOUR = self::THREE << 1;
    public const FIVE = self::FOUR << 1;

    /**
     * @param string $name
     * @return string
     */
    public static function getFormattedName(string $name): string
    {
        return str_replace('_', '-', strtolower($name));
    }

}