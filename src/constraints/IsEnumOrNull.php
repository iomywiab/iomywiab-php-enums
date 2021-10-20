<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: IsEnumOrNull.php
 * Class name...: IsEnumOrNull.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-20 20:38:50
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums\constraints;

use iomywiab\iomywiab_php_constraints\exceptions\ConstraintViolationException;
use iomywiab\iomywiab_php_constraints\Format;
use iomywiab\iomywiab_php_enums\EnumDefinition;
use iomywiab\iomywiab_php_enums\exceptions\InvalidEnumDefinitionException;

/**
 * Class IsEnum
 * @package iomywiab\iomywiab_php_enums\constraints
 */
class IsEnumOrNull extends IsEnum
{
    /**
     * @param string      $className
     * @param             $value
     * @param string|null $valueName
     * @param array|null  $errors
     * @return bool
     * @throws ConstraintViolationException
     */
    public static function isValid(string $className, $value, ?string $valueName = null, array &$errors = null): bool
    {
        if ((null === $value) || parent::isValid($className, $value)) {
            return true;
        }

        if (null !== $errors) {
            try {
                $definition = EnumDefinition::getInstance($className);
                $message = null;
            } catch (InvalidEnumDefinitionException $cause) {
                $message = $cause->getMessage();
                $definition = null;
            }

            $format = empty($message)
                ? 'Enumeration value of [%s] expected. Valid values are: '
                . Format::toValueList($definition->getAllNames())
                : 'Class [%s] does not identify an enumeration. ' . $message;
            $errors[] = self::toErrorMessage($value, $valueName, $format, $className);
        }
        return false;
    }

}