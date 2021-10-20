<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: EnumSetInterface.php
 * Class name...: EnumSetInterface.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-20 20:38:50
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums\interfaces;

use iomywiab\iomywiab_php_enums\enums\EnumFormat;
use iomywiab\iomywiab_php_enums\EnumValue;
use iomywiab\iomywiab_php_enums\exceptions\EnumNotFoundException;
use iomywiab\iomywiab_php_enums\exceptions\InvalidEnumDefinitionException;
use iomywiab\iomywiab_php_enums\exceptions\NotSameEnumTypeException;
use SplSubject;

/**
 * Class EnumSet
 * @package iomywiab\iomywiab_php_enums
 */
interface EnumSetInterface extends SplSubject
{
    /**
     * @return EnumDefinitionInterface
     */
    public function getDefinition(): EnumDefinitionInterface;

    /**
     * @param int|string|EnumValueInterface|EnumSetInterface|int[]|string[]|EnumValueInterface[] $value
     * @return EnumSetInterface
     * @throws EnumNotFoundException
     */
    public function add($value): EnumSetInterface;

    /**
     * @param int|string|EnumValueInterface|EnumSetInterface|int[]|string[]|EnumValueInterface[] $value
     * @return EnumSetInterface
     */
    public function remove($value): EnumSetInterface;

    /**
     * @param int|string|EnumValueInterface $value
     * @return bool
     */
    public function contains($value): bool;

    /**
     * @return EnumSetInterface
     */
    public function clear(): EnumSetInterface;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @return EnumSetInterface
     */
    public function copy(): EnumSetInterface;

    /**
     * @param EnumSetInterface $other
     * @return EnumSetInterface
     * @throws NotSameEnumTypeException
     * @throws InvalidEnumDefinitionException
     */
    public function diff(EnumSetInterface $other): EnumSetInterface;

    /**
     * @param EnumSetInterface $other
     * @return EnumSetInterface
     * @throws NotSameEnumTypeException
     * @throws InvalidEnumDefinitionException
     */
    public function intersect(EnumSetInterface $other): EnumSetInterface;

    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @param EnumSetInterface $other
     * @return EnumSetInterface
     * @throws NotSameEnumTypeException
     * @throws InvalidEnumDefinitionException
     */
    public function merge(EnumSetInterface $other): EnumSetInterface;

    /**
     * @param int|string|EnumValueInterface|null $format
     * @return EnumValue[]
     * @throws EnumNotFoundException
     */
    public function toArray($format = EnumFormat::NAME): array;

    /**
     * @param string                             $glue
     * @param int|string|EnumValueInterface|null $format
     * @return string
     * @throws EnumNotFoundException
     */
    public function toString(string $glue = ',', $format = EnumFormat::NAME): string;

    /**
     * @param EnumSetInterface $other
     * @throws NotSameEnumTypeException
     */
    public function assertSameDefinition(EnumSetInterface $other): void;

}