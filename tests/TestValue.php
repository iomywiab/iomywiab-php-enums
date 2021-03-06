<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2022 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: TestValue.php
 * Class name...: TestValue.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2022-02-21 13:55:38
 */

/** @noinspection SpellCheckingInspection */

/** @noinspection PhpUnused */
/** @noinspection PhpConstantNamingConventionInspection */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums_tests;

use iomywiab\iomywiab_php_enums\EnumValue;

/**
 * Class ExampleValue
 * @package iomywiab\iomywiab_php_enums_tests
 */
class TestValue extends EnumValue
{
    public const ONE = 111;
    public const Two = 222;
    public const three = 333;
    public const FOUR = 444;
    public const Five = 555;

    public const DISPLAY = [
        self::ONE => 'Eins',
        self::FOUR => 'Vier'
    ];
    public const SecondaryKey = [
        self::ONE => 1,
        self::Five => 5
    ];
    public const tEST = [
        self::ONE => 111111,
        self::three => 333333
    ];

    /**
     * @param string $name
     * @return string
     */
    public static function getFormattedName(string $name): string
    {
        return $name; //$name = str_replace('_', '-', strtolower($name));
    }

}