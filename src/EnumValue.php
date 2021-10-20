<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: EnumValue.php
 * Class name...: EnumValue.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-20 20:38:50
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums;

use AssertionError;
use LogicException;
use iomywiab\iomywiab_php_design_patterns\properties\GettersTrait;
use iomywiab\iomywiab_php_enums\exceptions\EnumAttributeNotFoundException;
use iomywiab\iomywiab_php_enums\exceptions\EnumException;
use iomywiab\iomywiab_php_enums\exceptions\EnumNotFoundException;
use iomywiab\iomywiab_php_enums\exceptions\InvalidEnumDefinitionException;
use iomywiab\iomywiab_php_enums\interfaces\EnumDefinitionInterface;
use iomywiab\iomywiab_php_enums\interfaces\EnumValueInterface;

/**
 * Class EnumValue
 * @package iomywiab\iomywiab_php_enums
 */
abstract class EnumValue implements EnumValueInterface
{
    use GettersTrait;

//    /**
//     * Optional overwrite: Change formatting of the name
//     * @param string $name
//     * @return string
//     */
//    public static function getFormattedName(string $name): string {
//        return str_replace('_', '-', strtolower($name));
//    }

    /**
     * @var int
     */
    protected $ordinal;

    /**
     * @var EnumDefinitionInterface
     */
    protected $definition;

    /**
     * EnumValue constructor.
     * @param int|string|EnumValueInterface|null $ordinalOrName
     * @throws EnumNotFoundException
     */
    public function __construct($ordinalOrName)
    {
        try {
            $this->definition = static::getStaticDefinition();
        } catch (InvalidEnumDefinitionException $cause) {
            throw new EnumNotFoundException(null, $ordinalOrName, $cause->getMessage(), $cause);
        }
        $this->ordinal = $this->definition->getOrdinal($ordinalOrName);
    }

    /**
     * @param $ordinalOrName
     * @param $arguments
     * @return EnumValueInterface|EnumDefinitionInterface
     * @throws EnumNotFoundException
     * @throws InvalidEnumDefinitionException
     */
    public static function __callStatic($ordinalOrName, $arguments)
    {
        if (!empty($arguments)) {
            throw new AssertionError(
                'Method [' . $ordinalOrName . '] of class [' . static::class . '] does not expect any parameters'
            );
        }

        if ('definition' === $ordinalOrName) {
            return static::getStaticDefinition();
        }

        return new static($ordinalOrName);
    }

    /**
     * @inheritDoc
     */
    public function getDefinition(): EnumDefinitionInterface
    {
        return $this->definition;
    }

    /**
     * @inheritDoc
     */
    public function getOrdinal(): int
    {
        return $this->ordinal;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        try {
            return $this->definition->getName($this->ordinal);
        } catch (EnumException $cause) {
            throw new LogicException('Unable to get name of enum', $cause);
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws EnumNotFoundException
     * @throws EnumAttributeNotFoundException
     */
    public function __call($name, $arguments)
    {
        if (strpos($name, 'is') === 0) {
            if (!empty($arguments)) {
                throw new AssertionError(
                    'Method [' . $name . '] of class [' . static::class . '] does not expect any parameters'
                );
            }

            $name = static::getFormattedName(substr($name, 2));
            $requested = $this->definition->getOrdinal($name);

            return $requested === $this->ordinal;
        }

        if (strpos($name, 'get') === 0) {
            if (!empty($arguments)) {
                throw new AssertionError(
                    'Method [' . $name . '] of class [' . static::class . '] does not expect any parameter'
                );
            }

            $name = substr($name, 3);
            return $this->definition->getAttribute($name, $this->ordinal);
        }

        return new static($name);
    }

    /**
     * @inheritDoc
     */
    public function serialize(): string
    {
        return serialize($this->ordinal);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($data): void
    {
        $this->ordinal = unserialize($data, ['allowed_classes' => false]);
    }

    /**
     * @return EnumDefinitionInterface
     * @throws InvalidEnumDefinitionException
     */
    public static function getStaticDefinition(): EnumDefinitionInterface
    {
        return EnumDefinition::getInstance(static::class);
    }

    /**
     * @inheritDoc
     */
    public function toDisplay(): string
    {
        try {
            return $this->definition->getName($this->ordinal) . '(' . $this->ordinal . ')';
        } catch (EnumException $cause) {
            throw new LogicException('unable to build string from enum', $cause);
        }
    }

}
