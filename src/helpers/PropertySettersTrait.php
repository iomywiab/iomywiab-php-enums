<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: PropertySettersTrait.php
 * Class name...: PropertySettersTrait.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-21 08:08:25
 */

declare(strict_types=1);
namespace iomywiab\iomywiab_php_enums\helpers;

use LogicException;

/**
 * Trait Setters
 * @package iomywiab\iomywiab_php_enums\helpers
 */
trait PropertySettersTrait
{
    /**
     * @param string $name
     * @param $value
     * @return void
     * @noinspection MagicMethodsValidityInspection
     */
    public function __set(string $name, $value): void
    {
        $name = 'set' . ucfirst($name);
        if (method_exists($this, $name)) {
            $this->{$name}($value);
        } else {
            throw new LogicException('Method or property [' . $name . '] does not exists or is not accessible');
        }
    }
}
