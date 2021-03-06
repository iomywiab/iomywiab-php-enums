<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: EnumFormat.php
 * Class name...: EnumFormat.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-21 08:08:25
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums\enums;

use iomywiab\iomywiab_php_enums\EnumValue;

/**
 * Class EnumFormat
 * @package iomywiab\iomywiab_php_enums\enums
 */
class EnumFormat extends EnumValue
{
    public const ENUM_OBJECT = 1;
    public const NAME = self::ENUM_OBJECT << 1;
    public const ORDINAL = self::NAME << 1;

    /**
     * @param string $name
     * @return string
     */
    public static function getFormattedName(string $name): string
    {
        return str_replace('_', '-', strtolower($name));
    }

}