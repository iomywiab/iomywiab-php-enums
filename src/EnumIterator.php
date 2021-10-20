<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: EnumIterator.php
 * Class name...: EnumIterator.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-20 20:38:50
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums;

use Iterator;
use iomywiab\iomywiab_php_enums\interfaces\EnumDefinitionInterface;
use iomywiab\iomywiab_php_enums\interfaces\EnumValueInterface;

/**
 * Class EnumDefinition
 *
 * @package iomywiab\iomywiab_php_enums\enums
 */
class EnumIterator implements Iterator
{
    /**
     * @var EnumDefinitionInterface
     */
    private $definition;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * EnumIterator constructor.
     * @param EnumDefinitionInterface $definition
     */
    public function __construct(EnumDefinitionInterface $definition)
    {
        $this->definition = $definition;
    }

    /**
     * @return EnumValueInterface
     * @throws exceptions\EnumException
     */
    public function current(): EnumValueInterface
    {
        return $this->definition->getEnum($this->key());
    }

    /**
     * @return void
     */
    public function next(): void
    {
        $this->position++;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->definition->getAllOrdinals()[$this->position];
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->definition->getAllOrdinals()[$this->position]);
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

}
