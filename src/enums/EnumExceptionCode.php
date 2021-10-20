<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: EnumExceptionCode.php
 * Class name...: EnumExceptionCode.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-20 20:38:50
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums\enums;

use iomywiab\iomywiab_php_enums\EnumValue;

/**
 * Class EnumFormat
 * @package iomywiab\iomywiab_php_enums\enums
 */
class EnumExceptionCode extends EnumValue
{
    public const ATTRIBUTE_NOT_FOUND = 1;
    public const ENUM_NOT_FOUND = self::ATTRIBUTE_NOT_FOUND << 1;
    public const INVALID_DEFINITION = self::ENUM_NOT_FOUND << 1;
    public const INVALID_NAME = self::INVALID_DEFINITION << 1;
    public const INVALID_ORDINAL = self::INVALID_NAME << 1;
    public const NOT_SAME_ENUM_TYPE = self::INVALID_ORDINAL << 1;
    public const UNABLE_TO_CREATE_ENUM = self::NOT_SAME_ENUM_TYPE << 1;
    public const UNSUPPORTED_ENUM = self::UNABLE_TO_CREATE_ENUM << 1;
    public const UNSUPPORTED_VALUE = self::UNSUPPORTED_ENUM << 1;

    /**
     * @param string $name
     * @return string
     */
    public static function getFormattedName(string $name): string
    {
        return str_replace('_', '-', strtolower($name));
    }

}