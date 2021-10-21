<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: TestDuplicate.php
 * Class name...: TestDuplicate.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-21 07:18:23
 */

/** @noinspection PhpConstantNamingConventionInspection */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums_tests;

use iomywiab\iomywiab_php_enums\EnumValue;

/**
 * Class ExampleClass
 * @package iomywiab\iomywiab_php_enums_tests
 */
class TestDuplicate extends EnumValue
{
    public const ONE = 111;
    public const Two = 222;
    public const three = 333;
    public const FOUR = 444;
    public const Five = 333;
}