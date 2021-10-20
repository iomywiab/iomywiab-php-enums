<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: UnsupportedEnumException.php
 * Class name...: UnsupportedEnumException.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-20 20:38:50
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums\exceptions;

use LogicException;
use iomywiab\iomywiab_php_enums\enums\EnumExceptionCode;
use iomywiab\iomywiab_php_enums\interfaces\EnumValueInterface;
use Throwable;

/**
 * Class UnsupportedEnumException
 *
 * Used in switch statements in case the default is reached.
 * @see UnsupportedValueException If switch statement is not based on EnumValue then use UnsupportedValueException
 *
 * @package iomywiab\iomywiab_php_enums\exceptions
 */
class UnsupportedEnumException extends LogicException implements EnumException
{

    /**
     * UnsupportedEnumException constructor.
     *
     * @param null|EnumValueInterface $value
     * @param null|string             $nameOfMethod
     * @param null|string             $comment
     * @param Throwable|null          $previous
     */
    public function __construct(
        ?EnumValueInterface $value,
        ?string $nameOfMethod,
        ?string $comment = null,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            self::createMessage($value, $nameOfMethod, $comment),
            EnumExceptionCode::UNSUPPORTED_ENUM,
            $previous
        );
    }

    /**
     * @param null|EnumValueInterface $value
     * @param null|string             $nameOfMethod
     * @param null|string             $comment
     *
     * @return string
     */
    private static function createMessage(
        ?EnumValueInterface $value,
        ?string $nameOfMethod,
        ?string $comment
    ): string {
        $value = ($value === null
            ? 'null'
            : $value->getDefinition()->getEnumClassName() . ':[' . $value->getName() . '/' . $value->getOrdinal() . ']'
        );
        $nameOfMethod = empty($nameOfMethod) ? 'n/a' : $nameOfMethod;

        $message = 'Unsupported enum value [' . $value . '] in [' . $nameOfMethod
            . '] (value might have been unknown at time of implementation)';

        if (!empty($comment)) {
            $message .= ': ';
            $message .= $comment;
        }

        return $message;
    }

}
