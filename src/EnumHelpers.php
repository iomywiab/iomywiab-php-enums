<?php

/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: EnumHelpers.php
 * Class name...: EnumHelpers.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-11-11 17:27:49
 */
declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums;

use iomywiab\iomywiab_php_enums\interfaces\EnumDefinitionInterface;

class EnumHelpers
{
    /**
     * @param EnumDefinitionInterface $definition
     * @param array|null              $array
     * @return array
     */
    public static function translateArrayKeys(EnumDefinitionInterface $definition, ?array $array): array
    {
        if (empty($array)) {
            return [];
        }

        $ar = [];
        foreach ($array as $key => $value) {
            $index = is_int($key) ? $definition->getName($key) : $definition->getOrdinal($key);
            $ar[$index] = $value;
        }
        return $ar;
    }
}