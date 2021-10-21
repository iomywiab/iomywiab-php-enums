<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: PropertyGettersTrait.php
 * Class name...: PropertyGettersTrait.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-21 08:08:25
 */

declare(strict_types=1);
namespace iomywiab\iomywiab_php_enums\helpers;

use LogicException;

/**
 * Trait Getters
 * @package iomywiab\iomywiab_php_enums\helpers
 */
trait PropertyGettersTrait
{
    /**
     * @param string $name
     * @return mixed
     * @noinspection MagicMethodsValidityInspection
     */
    public function __get(string $name)
    {
        $name = 'get' . ucfirst($name);

        if (!method_exists($this, $name)) {
            throw new LogicException('Method or property [' . $name . '] does not exists');
        }

        return $this->{$name}();
    }

}