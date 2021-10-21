<?php

/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: EnumDefinitionInterface.php
 * Class name...: EnumDefinitionInterface.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-21 08:08:25
 */
declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums\interfaces;

use IteratorAggregate;
use iomywiab\iomywiab_php_enums\enums\EnumFormat;
use iomywiab\iomywiab_php_enums\EnumValue;
use iomywiab\iomywiab_php_enums\exceptions\EnumAttributeNotFoundException;
use iomywiab\iomywiab_php_enums\exceptions\EnumNotFoundException;
use iomywiab\iomywiab_php_enums\exceptions\InvalidEnumNameException;
use iomywiab\iomywiab_php_enums\exceptions\InvalidEnumOrdinalException;
use iomywiab\iomywiab_php_enums\exceptions\NotSameEnumTypeException;

/**
 * Interface EnumDefinitionInterface
 * @package iomywiab\iomywiab_php_enums\interfaces
 */
interface EnumDefinitionInterface extends IteratorAggregate
{
    /**
     * @return string
     */
    public function getEnumClassName(): string;

    /**
     * @return bool
     */
    public function isBitmaskEnum(): bool;

    /**
     * @param int|string|EnumValueInterface|null $enumValue
     * @return bool
     */
    public function has($enumValue): bool;

    /**
     * @param int $ordinal
     * @return bool
     */
    public function hasOrdinal(int $ordinal): bool;

    /**
     * @param string $name
     * @return bool
     */
    public function hasName(string $name): bool;

    /**
     * @param EnumValueInterface $enum
     * @return bool
     */
    public function hasEnum(EnumValueInterface $enum): bool;

    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool;

    /**
     * @param int|string|EnumValueInterface|null $enumValue
     * @return int
     * @throws EnumNotFoundException
     */
    public function getOrdinal($enumValue): int;

    /**
     * @param int|string|EnumValueInterface|null $enumValue
     * @return string
     * @throws EnumNotFoundException
     */
    public function getName($enumValue): string;

    /**
     * @param int|string|EnumValueInterface|null $enumValue
     * @return EnumValueInterface
     * @throws EnumNotFoundException
     */
    public function getEnum($enumValue): EnumValueInterface;

    /**
     * @return int[] undefined(int) => ordinal(int)
     */
    public function getAllOrdinals(): array;

    /**
     * @return int[] undefined(int) => ordinal(int)
     * @deprecated use getAllOrdinals() instead
     */
    public function getOrdinals(): array;

    /**
     * @return string[] ordinal(int) => name(string)
     */
    public function getAllNames(): array;

    /**
     * @return string[] ordinal(int) => name(string)
     * @deprecated use getAllNames() instead
     */
    public function getNames(): array;

    /**
     * @return EnumValueInterface[] undefined(int) => enum(EnumValueInterface)
     */
    public function getAllEnums(): array;

    /**
     * @param string                             $attributeName
     * @param int|string|EnumValueInterface|null $enumValue
     * @return mixed|null
     * @throws EnumAttributeNotFoundException
     */
    public function getAttribute(string $attributeName, $enumValue);

    /**
     * @param string                             $attributeName
     * @param int|string|EnumValueInterface|null $enumValue
     * @param mixed                              $attributeValue
     * @return mixed|null
     * @throws EnumAttributeNotFoundException
     */
    public function setAttribute(string $attributeName, $enumValue, $attributeValue): EnumDefinitionInterface;

    /**
     * @param string $attributeName
     * @return array
     * @throws EnumAttributeNotFoundException
     */
    public function getAllAttributes(string $attributeName): array;

    /**
     * @param string $attributeName
     * @param array  $attributes
     * @return EnumDefinitionInterface
     * @throws EnumNotFoundException
     * @throws EnumAttributeNotFoundException
     */
    public function setAllAttributes(string $attributeName, array $attributes): EnumDefinitionInterface;

    /**
     * @param EnumValueInterface $enum
     * @return bool
     */
    public function isSameType(EnumValueInterface $enum): bool;

    /**
     * @param int|null $ordinal
     * @return bool
     */
    public function isValidOrdinal(?int $ordinal): bool;

    /**
     * @param string|null $name
     * @return bool
     */
    public function isValidName(?string $name): bool;

    /**
     * @param EnumValueInterface $enum
     * @throws NotSameEnumTypeException
     */
    public function assertSameType(EnumValueInterface $enum): void;

    /**
     * @param int|null $ordinal
     * @throws InvalidEnumOrdinalException
     */
    public function assertValidOrdinal(?int $ordinal): void;

    /**
     * @param string|null $name
     * @throws InvalidEnumNameException
     */
    public function assertValidName(?string $name): void;

    /**
     * @param int|string|EnumValueInterface|null $enumValue
     * @throws EnumNotFoundException
     */
    public function assertExists($enumValue): void;

    /**
     * @param int $ordinal
     * @throws EnumNotFoundException
     */
    public function assertOrdinalExists(int $ordinal): void;

    /**
     * @param string $name
     * @throws EnumNotFoundException
     */
    public function assertNameExists(string $name): void;

    /**
     * @param string $attributeName
     * @throws EnumAttributeNotFoundException
     */
    public function assertAttributeExists(string $attributeName): void;

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

}