<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: EnumSet.php
 * Class name...: EnumSet.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-20 20:38:50
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums;

use LogicException;
use iomywiab\iomywiab_php_enums\enums\EnumFormat;
use iomywiab\iomywiab_php_enums\exceptions\EnumException;
use iomywiab\iomywiab_php_enums\exceptions\InvalidEnumDefinitionException;
use iomywiab\iomywiab_php_enums\exceptions\NotSameEnumTypeException;
use iomywiab\iomywiab_php_enums\exceptions\UnsupportedEnumException;
use iomywiab\iomywiab_php_enums\interfaces\EnumDefinitionInterface;
use iomywiab\iomywiab_php_enums\interfaces\EnumSetInterface;
use iomywiab\iomywiab_php_enums\interfaces\EnumValueInterface;

/**
 * Class EnumSet
 * @package iomywiab\iomywiab_php_enums
 */
class EnumSet implements EnumSetInterface
{
    use SplSubjectTrait;

    /**
     * @var int[]|int bitmask | ordinal => 1
     */
    private $ordinals;

    /**
     * @var EnumDefinitionInterface
     */
    private $definition;

    /**
     * EnumSet constructor.
     * @param EnumValueInterface|EnumDefinitionInterface|string $enumClass
     * @throws InvalidEnumDefinitionException
     */
    public function __construct($enumClass)
    {
        if ($enumClass instanceof EnumValueInterface) {
            $this->definition = $enumClass->getDefinition();
        } elseif ($enumClass instanceof EnumDefinitionInterface) {
            $this->definition = $enumClass;
        } elseif (is_string($enumClass)) {
            $this->definition = EnumDefinition::getInstance($enumClass);
        } else {
            $types = EnumValueInterface::class . '|' . EnumDefinitionInterface::class . '|string]';
            $message = 'Parameter [enumClass] must be of type [' . $types . ']';
            throw new InvalidEnumDefinitionException($enumClass, $message);
        }
        $this->ordinals = $this->definition->isBitmaskEnum() ? 0 : [];
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
    public function add($value): EnumSetInterface
    {
        $isBitmask = $this->definition->isBitmaskEnum();
        if ($value instanceof EnumSetInterface) {
            if ($isBitmask) {
                if (($this->ordinals & $value->ordinals) !== $value->ordinals) {
                    $this->ordinals |= $value->ordinals;
                    $this->notify();
                }
                return $this;
            }

            $value = $value->ordinals;
        }

        $changed = false;
        if (!is_array($value)) {
            $value = [$value];
        }
        foreach ($value as $item) {
            $ordinal = $this->definition->getOrdinal($item);
            if ($isBitmask) {
                if (!($this->ordinals & $ordinal)) {
                    $this->ordinals |= $ordinal;
                    $changed = true;
                }
            } elseif (!array_key_exists($ordinal, $this->ordinals)) {
                $this->ordinals[$ordinal] = 1;
                $changed = true;
            }
        }

        if ($changed) {
            $this->notify();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function remove($value): EnumSetInterface
    {
        $isBitmask = $this->definition->isBitmaskEnum();
        if ($value instanceof EnumSetInterface) {
            if ($isBitmask) {
                if ($this->ordinals & $value->ordinals) {
                    $this->ordinals &= (PHP_INT_MAX ^ $value->ordinals);
                    $this->notify();
                }
                return $this;
            }

            $value = $value->ordinals;
        }

        $changed = false;
        if (!is_array($value)) {
            $value = [$value];
        }
        foreach ($value as $item) {
            try {
                $ordinal = $this->definition->getOrdinal($item);
                if ($isBitmask) {
                    if ($this->ordinals & $ordinal) {
                        $this->ordinals &= (PHP_INT_MAX ^ $ordinal);
                        $changed = true;
                    }
                } elseif (array_key_exists($ordinal, $this->ordinals)) {
                    unset($this->ordinals[$ordinal]);
                    $changed = true;
                }
            } catch (EnumException $ignore) {
                // no code
            }
        }

        if ($changed) {
            $this->notify();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function contains($value): bool
    {
        try {
            $ordinal = $this->definition->getOrdinal($value);
            if ($this->definition->isBitmaskEnum()) {
                return 0 !== ($this->ordinals & $ordinal);
            }

            return array_key_exists($ordinal, $this->ordinals);
        } catch (EnumException $ignore) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function clear(): EnumSetInterface
    {
        if ($this->definition->isBitmaskEnum()) {
            if (0 !== $this->ordinals) {
                $this->ordinals = 0;
                $this->notify();
            }
        } elseif (!empty($this->ordinals)) {
            $this->ordinals = [];
            $this->notify();
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->definition->isBitmaskEnum() ? $this->getBitCount($this->ordinals) : count($this->ordinals);
    }

    /**
     * @param int $value
     * @return int
     */
    protected function getBitCount(int $value): int
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
    public function copy(): EnumSetInterface
    {
        try {
            $enum = new static($this->definition);
            $enum->ordinals = $this->ordinals;
            return $enum;
        } catch (EnumException $cause) {
            throw new LogicException('Unable to copy enum set', $cause);
        }
    }

    /**
     * @inheritDoc
     */
    public function diff(EnumSetInterface $other): EnumSetInterface
    {
        $this->assertSameDefinition($other);
        $diff = new static($this->definition);
        $diff->ordinals = $this->definition->isBitmaskEnum()
            ? $this->ordinals ^ $other->ordinals
            : array_flip(
                array_merge(
                    array_diff(array_keys($this->ordinals), array_keys($other->ordinals)),
                    array_diff(array_keys($other->ordinals), array_keys($this->ordinals))
                )
            );
        return $diff;
    }

    /**
     * @inheritDoc
     */
    public function assertSameDefinition(EnumSetInterface $other): void
    {
        if ($this->definition !== $other->definition) {
            throw new NotSameEnumTypeException($this->definition, $other->definition);
        }
    }

    /**
     * @inheritDoc
     */
    public function intersect(EnumSetInterface $other): EnumSetInterface
    {
        $this->assertSameDefinition($other);
        $diff = new static($this->definition);
        $diff->ordinals = $this->definition->isBitmaskEnum()
            ? $this->ordinals & $other->ordinals
            : array_flip(array_intersect(array_keys($this->ordinals), array_keys($other->ordinals)));
        return $diff;
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return empty($this->ordinals);
    }

    /**
     * @inheritDoc
     */
    public function merge(EnumSetInterface $other): EnumSetInterface
    {
        $this->assertSameDefinition($other);
        $diff = new static($this->definition);
        $diff->ordinals = $this->definition->isBitmaskEnum()
            ? $this->ordinals | $other->ordinals
            : array_flip(array_merge(array_keys($this->ordinals), array_keys($other->ordinals)));
        return $diff;
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
                if ($this->definition->isBitmaskEnum()) {
                    foreach ($this->definition->getAllOrdinals() as $ordinal) {
                        if ($ordinal & $this->ordinals) {
                            $return[] = $this->definition->getEnum($ordinal);
                        }
                    }
                } else {
                    foreach (array_keys($this->ordinals) as $ordinal) {
                        $return[] = $this->definition->getEnum($ordinal);
                    }
                }
                break;
            case EnumFormat::NAME:
                if ($this->definition->isBitmaskEnum()) {
                    foreach ($this->definition->getAllOrdinals() as $ordinal) {
                        if ($ordinal & $this->ordinals) {
                            $return[] = $this->definition->getName($ordinal);
                        }
                    }
                } else {
                    foreach (array_keys($this->ordinals) as $ordinal) {
                        $return[] = $this->definition->getName($ordinal);
                    }
                }
                break;
            case EnumFormat::ORDINAL:
                if ($this->definition->isBitmaskEnum()) {
                    foreach ($this->definition->getAllOrdinals() as $ordinal) {
                        if ($ordinal & $this->ordinals) {
                            $return[] = $ordinal;
                        }
                    }
                } else {
                    $return = array_keys($this->ordinals);
                }
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
                if ($this->definition->isBitmaskEnum()) {
                    foreach ($this->definition->getAllOrdinals() as $ordinal) {
                        if ($ordinal & $this->ordinals) {
                            $return .= $separator . $this->definition->getName($ordinal);
                            $separator = $glue;
                        }
                    }
                } else {
                    foreach (array_keys($this->ordinals) as $ordinal) {
                        $return .= $separator . $this->definition->getName($ordinal);
                        $separator = $glue;
                    }
                }
                break;
            case EnumFormat::ORDINAL:
                if ($this->definition->isBitmaskEnum()) {
                    foreach ($this->definition->getAllOrdinals() as $ordinal) {
                        if ($ordinal & $this->ordinals) {
                            $return .= $separator . $ordinal;
                            $separator = $glue;
                        }
                    }
                } else {
                    foreach (array_keys($this->ordinals) as $ordinal) {
                        $return .= $separator . $ordinal;
                        $separator = $glue;
                    }
                }
                break;
            default:
                throw new UnsupportedEnumException($format, __METHOD__);
        }

        return $return;
    }

}