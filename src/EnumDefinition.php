<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: EnumDefinition.php
 * Class name...: EnumDefinition.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-20 20:38:50
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums;

use Exception;
use LogicException;
use iomywiab\iomywiab_php_constraints\constraints\simple\IsNotEmpty;
use iomywiab\iomywiab_php_constraints\exceptions\ConstraintViolationException;
use iomywiab\iomywiab_php_enums\enums\EnumFormat;
use iomywiab\iomywiab_php_enums\exceptions\EnumAttributeNotFoundException;
use iomywiab\iomywiab_php_enums\exceptions\EnumException;
use iomywiab\iomywiab_php_enums\exceptions\EnumNotFoundException;
use iomywiab\iomywiab_php_enums\exceptions\InvalidEnumDefinitionException;
use iomywiab\iomywiab_php_enums\exceptions\InvalidEnumNameException;
use iomywiab\iomywiab_php_enums\exceptions\InvalidEnumOrdinalException;
use iomywiab\iomywiab_php_enums\exceptions\NotSameEnumTypeException;
use iomywiab\iomywiab_php_enums\exceptions\UnableToCreateEnumException;
use iomywiab\iomywiab_php_enums\exceptions\UnsupportedEnumException;
use iomywiab\iomywiab_php_enums\interfaces\EnumDefinitionInterface;
use iomywiab\iomywiab_php_enums\interfaces\EnumValueInterface;
use ReflectionClass;
use ReflectionException;


/**
 * Class EnumDefinition
 *
 * @package iomywiab\iomywiab_php_enums\enums
 */
class EnumDefinition implements EnumDefinitionInterface
{
    // All integer values are considered to be enum values
    // All array values are consiered to be attributes
    // Constants integer and arrays listed in array ENUM_IGNORE are ignored
    // All other values are ignored anyway
    public const ENUM_IGNORE = 'ENUM_IGNORE';

    /**
     * @var EnumDefinitionInterface[]|null className(string) => definition(EnumDefinitionInterface)
     */
    private static $definitions;

    /**
     * @param string $className
     * @return static
     * @throws InvalidEnumDefinitionException
     */
    public static function getInstance(string $className): EnumDefinition
    {
        IsNotEmpty::assert($className);
        if (empty(self::$definitions) || !array_key_exists($className, self::$definitions)) {
            self::$definitions[$className] = new static($className);
        }
        return self::$definitions[$className];
    }

    /**
     * @var ReflectionClass
     */
    protected $valueClass;

    /**
     * @var int[]|null key(int) => ordinal(int)
     */
    private $cachedOrdinals;

    /**
     * @var string[] [ordinal(int) => name(string)]
     */
    private $names;

    /**
     * @var bool
     */
    private $doFormatName = false;

    /**
     * @var array [attribute-key(int) => [ordinal(int) => attribute(mixed)]]
     */
    protected $attributes;

    /**
     * @var bool
     */
    private $isBitmaskEnum;

    /**
     * EnumDefinition constructor.
     * @param string     $className
     * @param array|null $ignores
     * @throws InvalidEnumDefinitionException
     */
    protected function __construct(string $className, ?array $ignores = null)
    {
        try {
            IsNotEmpty::assert('className', $className);

            $this->valueClass = new ReflectionClass ($className);
            $constants = $this->valueClass->getConstants();

            // remove ignored values from $constants to be not processed here
            if (!empty($constants)) {
                if (!empty($ignores)) {
                    foreach ($ignores as $ignore) {
                        unset($constants[$ignore]);
                    }
                }

                if (array_key_exists(self::ENUM_IGNORE, $constants)) {
                    $ignores = $constants[self::ENUM_IGNORE];
                    if (is_array($ignores)) {
                        foreach ($ignores as $ignore) {
                            unset($constants[$ignore]);
                        }
                    } else {
                        unset($constants[$ignores]);
                    }
                }
            }

            $ordinals = [];
            $this->attributes = [];
            if (!empty($constants)) {
                foreach ($constants as $name => $value) {
                    if (is_int($value)) {
                        $ordinals[$name] = $value;
                    } elseif (is_array($value)) {
                        $this->attributes[$name] = $value;
                    }
                }
            }

            if (empty($ordinals)) {
                $message = 'No values defined in enum [' . $this->valueClass->getName() . '].';
                throw new InvalidEnumDefinitionException($className, $message);
            }

            $this->names = array_flip($ordinals);

            if (count($this->names) !== count($ordinals)) {
                $message = 'Values of constants must be unique in enum [' . $this->valueClass->getName() . '].';
                throw new InvalidEnumDefinitionException($className, $message);
            }

            // check attribute structure
            if (!empty($this->attributes)) {
                foreach ($this->attributes as $attributeName => $attributes) {
                    foreach ($attributes as $ordinal => $value) {
                        if (!array_key_exists($ordinal, $this->names)) {
                            $message = 'Undefined ordinal [' . $ordinal . '] used in attribute [' . $attributeName
                                . '] in enum [' . $this->valueClass->getName() . '].';
                            throw new InvalidEnumDefinitionException($className, $message);
                        }
                    }
                }
            }

            // format names
            if ($this->valueClass->hasMethod('getFormattedName')) {
                $methodInstance = $this->valueClass->getMethod('getFormattedName');
                if (!$methodInstance->isPublic() || !$methodInstance->isStatic()) {
                    $message = 'If you define method [getFormattedName] then it must be a public static function';
                    throw new InvalidEnumDefinitionException($className, $message);
                }
                $parameters = $methodInstance->getParameters();
                if (empty($parameters)
                    || (1 !== count($parameters))
                    || ('name' !== $parameters[0]->name)
                    || ($parameters[0]->isPassedByReference())
                    || ('string' !== $parameters[0]->getType()->getName())
                ) {
                    $message = 'If you define method [getFormattedName] then it must define parameter "string $name"';
                    throw new InvalidEnumDefinitionException($className, $message);
                }
                $returnType = $methodInstance->getReturnType();
                if (('string' !== $returnType->getName()) || $returnType->allowsNull()) {
                    $message = 'If you define method [getFormattedName] then it must return a non-null string';
                    throw new InvalidEnumDefinitionException($className, $message);
                }
                $funcName = $this->valueClass->getName() . '::getFormattedName';
                foreach ($this->names as &$name) {
                    $name = $funcName($name);
                }
                unset($name);
                $this->doFormatName = true;
            }

            $this->isBitmaskEnum = true;
            foreach (array_keys($this->names) as $ordinal) {
                $count = self::getBitCount($ordinal);
                if (1 !== $count) {
                    $this->isBitmaskEnum = false;
                    break;
                }
            }
        } catch (ConstraintViolationException | ReflectionException $cause) {
            throw new InvalidEnumDefinitionException($className, $cause->getMessage(), $cause);
        }
    }

    /**
     * @param int $value
     * @return int
     */
    protected static function getBitCount(int $value): int
    {
        $count = 0;
        while ($value) {
            $count += ($value & 1);
            $value >>= 1;
        }

        return $count;
    }

    /**
     * @inheritDoc
     */
    public function getEnumClassName(): string
    {
        return $this->valueClass->getName();
    }

    /**
     * @inheritDoc
     */
    public function isBitmaskEnum(): bool
    {
        return $this->isBitmaskEnum;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getFormattedName(string $name): string
    {
        $funcName = $this->valueClass->getName() . '::getFormattedName';
        return $funcName($name);
    }

    /**
     * @inheritDoc
     */
    public function has($enumValue): bool
    {
        if (is_int($enumValue)) {
            return $this->hasOrdinal($enumValue);
        }

        if (is_numeric($enumValue) && ($enumValue === (int)$enumValue)) {
            return $this->hasOrdinal((int)$enumValue);
        }

        if (is_string($enumValue)) {
            return $this->hasName($enumValue);
        }

        if ($enumValue instanceof EnumValueInterface) {
            return $this->hasEnum($enumValue);
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function hasOrdinal(int $ordinal): bool
    {
        return array_key_exists($ordinal, $this->names);
    }

    /**
     * @inheritDoc
     */
    public function hasName(string $name): bool
    {
        if ($this->doFormatName) {
            $name = $this->getFormattedName($name);
        }
        return in_array($name, $this->names, true);
    }

    /**
     * @inheritDoc
     */
    public function hasEnum(EnumValueInterface $enum): bool
    {
        return $this->isSameType($enum);
    }

    /**
     * @inheritDoc
     */
    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * @inheritDoc
     */
    public function getOrdinal($enumValue): int
    {
        $comment = '';
        if (is_int($enumValue)) {
            if (array_key_exists($enumValue, $this->names)) {
                return $enumValue;
            }
        } elseif (is_numeric($enumValue) && ($enumValue === (int)$enumValue)) {
            $ordinal = (int)$enumValue;
            if (array_key_exists($ordinal, $this->names)) {
                return $ordinal;
            }
        } elseif (is_string($enumValue)) {
            if ($this->doFormatName) {
                $enumValue = $this->getFormattedName($enumValue);
            }
            $ordinal = array_search($enumValue, $this->names, true);
            if (false !== $ordinal) {
                return $ordinal;
            }
        } elseif (($enumValue instanceof EnumValueInterface) && $this->isSameType($enumValue)) {
            return $enumValue->getOrdinal();
        } /** @noinspection NotOptimalIfConditionsInspection */ elseif ($enumValue instanceof EnumValueInterface) {
            // type is invalid
            $comment = 'different enum types';
        }
        throw new EnumNotFoundException($this, $enumValue, $comment);
    }

    /**
     * @inheritDoc
     */
    public function getName($enumValue): string
    {
        $ordinal = $this->getOrdinal($enumValue);
        return $this->names[$ordinal];
    }

    /**
     * @inheritDoc
     */
    public function getEnum($enumValue): EnumValueInterface
    {
        $ordinal = $this->getOrdinal($enumValue);
        try {
            /**
             * @var EnumValueInterface $enum
             */
            /** @noinspection PhpUnnecessaryLocalVariableInspection */
            /** @noinspection OneTimeUseVariablesInspection */
            $enum = $this->valueClass->newInstance($ordinal);
            return $enum;
        } catch (Exception $cause) {
            $exc = new UnableToCreateEnumException($this, $enumValue, $cause);
            throw new EnumNotFoundException($this, $enumValue, $exc->getMessage(), $cause);
        }
    }

    /**
     * @inheritDoc
     */
    public function getAllOrdinals(): array
    {
        if (empty($this->cachedOrdinals)) {
            $this->cachedOrdinals = array_keys($this->names);
        }
        return $this->cachedOrdinals;
    }

    /**
     * @inheritDoc
     */
    public function getOrdinals(): array
    {
        return $this->getAllOrdinals();
    }

    /**
     * @inheritDoc
     */
    public function getAllNames(): array
    {
        return $this->names;
    }

    /**
     * @inheritDoc
     */
    public function getNames(): array
    {
        return $this->getAllNames();
    }

    /**
     * @inheritDoc
     */
    public function getAllEnums(): array
    {
        try {
            $enums = [];
            foreach ($this->getAllOrdinals() as $key) {
                $enums[] = $this->getEnum($key);
            }
            return $enums;
        } catch (EnumException $cause) {
            throw new LogicException('Unable to read already defined enum', $cause);
        }
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(string $attributeName, $enumValue)
    {
        $this->assertAttributeExists($attributeName);

        try {
            $ordinal = $this->getOrdinal($enumValue);
            return $this->attributes[$attributeName][$ordinal] ?? null;
        } catch (EnumNotFoundException $cause) {
            throw new EnumAttributeNotFoundException($this, $attributeName, $cause->getMessage(), $cause);
        }
    }

    /**
     * @inheritDoc
     */
    public function setAttribute(string $attributeName, $enumValue, $attributeValue): EnumDefinitionInterface
    {
        $this->assertAttributeExists($attributeName);

        try {
            $ordinal = $this->getOrdinal($enumValue);
            $this->attributes[$attributeName][$ordinal] = $attributeValue;
            return $this;
        } catch (EnumNotFoundException $cause) {
            throw new EnumAttributeNotFoundException($this, $attributeName, $cause->getMessage(), $cause);
        }
    }

    /**
     * @inheritDoc
     */
    public function getAllAttributes(string $attributeName): array
    {
        $this->assertAttributeExists($attributeName);
        return $this->attributes[$attributeName];
    }

    /**
     * @inheritDoc
     */
    public function setAllAttributes(string $attributeName, array $attributes): EnumDefinitionInterface
    {
        $this->assertAttributeExists($attributeName);
        foreach (array_keys($attributes) as $key) {
            $this->assertOrdinalExists($key);
        }
        $this->attributes[$attributeName] = $attributes;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isSameType(EnumValueInterface $enum): bool
    {
        return is_a($enum, $this->valueClass->getName());
    }

    /**
     * @inheritDoc
     */
    public function isValidOrdinal(?int $ordinal): bool
    {
        return (null !== $ordinal) && (1 <= $ordinal);
    }

    /**
     * @inheritDoc
     */
    public function isValidName(?string $name): bool
    {
        return !empty($name) && (1 === preg_match('/^[a-z][a-z0-9-]*$/', $name));
    }

    /**
     * @inheritDoc
     */
    public function assertSameType(EnumValueInterface $enum): void
    {
        if (!$this->isSameType($enum)) {
            throw new NotSameEnumTypeException($this, $enum->getDefinition());
        }
    }

    /**
     * @inheritDoc
     */
    public function assertValidOrdinal(?int $ordinal): void
    {
        if (!$this->isValidOrdinal($ordinal)) {
            throw new InvalidEnumOrdinalException($this, $ordinal);
        }
    }

    /**
     * @inheritDoc
     */
    public function assertValidName(?string $name): void
    {
        if (!$this->isValidName($name)) {
            throw new InvalidEnumNameException($this, $name);
        }
    }

    /**
     * @inheritDoc
     */
    public function assertExists($enumValue): void
    {
        if (!$this->has($enumValue)) {
            throw new EnumNotFoundException($this, $enumValue);
        }
    }

    /**
     * @inheritDoc
     */
    public function assertOrdinalExists(int $ordinal): void
    {
        if (!$this->hasOrdinal($ordinal)) {
            throw new EnumNotFoundException($this, $ordinal);
        }
    }

    /**
     * @inheritDoc
     */
    public function assertNameExists(string $name): void
    {
        if (!$this->hasName($name)) {
            throw new EnumNotFoundException($this, $name);
        }
    }

    /**
     * @inheritDoc
     */
    public function assertAttributeExists(string $attributeName): void
    {
        if (!$this->hasAttribute($attributeName)) {
            throw new EnumAttributeNotFoundException($this, $attributeName);
        }
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): EnumIterator
    {
        return new EnumIterator($this);
    }

    /**
     * @inheritDoc
     */
    public function toArray($format = EnumFormat::NAME): array
    {
        $return = [];
        $format = new EnumFormat(empty($format) ? EnumFormat::NAME : $format);

        switch ($format->getOrdinal()) {
            case EnumFormat::ENUM_OBJECT:
                foreach (array_keys($this->names) as $ordinal) {
                    $return[] = $this->getEnum($ordinal);
                }
                break;
            case EnumFormat::NAME:
                $return = $this->names;
                break;
            case EnumFormat::ORDINAL:
                if (empty($this->cachedOrdinals)) {
                    $this->cachedOrdinals = array_keys($this->names);
                }
                $return = $this->cachedOrdinals;
                break;
            default:
                throw new UnsupportedEnumException($format, __METHOD__);
        }

        return $return;
    }

    /**
     * @inheritDoc
     */
    public function toString(string $glue = ',', $format = EnumFormat::NAME): string
    {
        $return = '';
        $separator = '';
        $format = new EnumFormat(empty($format) ? EnumFormat::NAME : $format);

        switch ($format->getOrdinal()) {
            case EnumFormat::ENUM_OBJECT:
                $message = 'Format [' . $format->getName() . '] cannot be supported in method [toString]';
                throw new LogicException($message);
            case EnumFormat::NAME:
                try {
                    foreach (array_keys($this->names) as $ordinal) {
                        $return .= $separator . $this->getName($ordinal);
                        $separator = $glue;
                    }
                } catch (EnumNotFoundException $cause) {
                    throw new LogicException($cause->getMessage(), $cause);
                }
                break;
            case EnumFormat::ORDINAL:
                foreach (array_keys($this->names) as $ordinal) {
                    $return .= $separator . $ordinal;
                    $separator = $glue;
                }
                break;
            default:
                throw new UnsupportedEnumException($format, __METHOD__);
        }

        return $return;
    }

}
