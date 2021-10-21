<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: UnsupportedValueException.php
 * Class name...: UnsupportedValueException.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-21 08:08:25
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums\exceptions;

use LogicException;
use iomywiab\iomywiab_php_constraints\Format;
use iomywiab\iomywiab_php_enums\enums\EnumExceptionCode;
use Throwable;

/**
 * Class UnsupportedValueException
 *
 * Used in switch statements in case the default is reached and the switch statement does not rely on an EnumValue
 * @see UnsupportedEnumException If switch statement is based on EnumValue then use UnsupportedEnumException
 *
 * @package iomywiab\iomywiab_php_enums\exceptions
 */
class UnsupportedValueException extends LogicException implements EnumException
{

    /**
     * UnsupportedValueException constructor.
     *
     * @param null|mixed     $value
     * @param null|string    $nameOfMethod
     * @param null|string    $comment
     * @param array|null     $validValues
     * @param Throwable|null $previous
     */
    public function __construct(
        $value,
        ?string $nameOfMethod,
        ?string $comment = null,
        ?array $validValues = null,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            self::createMessage($value, $nameOfMethod, $comment, $validValues),
            EnumExceptionCode::UNSUPPORTED_VALUE,
            $previous
        );
    }

    /**
     * @param null|mixed  $value
     * @param null|string $nameOfMethod
     * @param null|string $comment
     * @param array|null  $validValues
     * @return string
     */
    protected static function createMessage(
        $value,
        ?string $nameOfMethod,
        ?string $comment,
        ?array $validValues
    ): string {
        $value = Format::toDescription($value);
        $nameOfMethod = empty($nameOfMethod) ? Format::NOT_AVAILABLE : $nameOfMethod;
        $validValues = empty($validValues) ? '' : '; Valid values are: ' . Format::toValueList($validValues);
        $comment = empty($comment) ? '' : ': ' . $comment;

        return 'Unsupported "enum" value [' . $value . '] in method [' . $nameOfMethod
            . ']' . $comment . $validValues . ' (value might have been unknown at time of implementation)';
    }

}
