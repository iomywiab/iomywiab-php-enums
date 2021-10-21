<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: InvalidEnumOrdinalException.php
 * Class name...: InvalidEnumOrdinalException.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-21 08:08:25
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums\exceptions;

use LogicException;
use iomywiab\iomywiab_php_constraints\Format;
use iomywiab\iomywiab_php_enums\enums\EnumExceptionCode;
use iomywiab\iomywiab_php_enums\interfaces\EnumDefinitionInterface;
use Throwable;

/**
 * Class InvalidEnumOrdinalException
 *
 * @package iomywiab\iomywiab_php_enums\exceptions
 */
class InvalidEnumOrdinalException extends LogicException implements EnumException
{

    /**
     * InvalidEnumOrdinalException constructor.
     * @param EnumDefinitionInterface|null $enum
     * @param int|null                     $ordinal
     * @param Throwable|null               $previous
     */
    public function __construct(
        ?EnumDefinitionInterface $enum,
        ?int $ordinal,
        ?Throwable $previous = null
    ) {
        parent::__construct(self::createMessage($enum, $ordinal), EnumExceptionCode::INVALID_ORDINAL, $previous);
    }

    /**
     * @param EnumDefinitionInterface|null $enum
     * @param int|null                     $ordinal
     * @return string
     */
    private static function createMessage(
        ?EnumDefinitionInterface $enum,
        ?int $ordinal
    ): string {
        $nam = Format::toType($enum);
        $ord = Format::toString($ordinal);
        $validValues = Format::toValueList($enum === null ? [] : $enum->getAllNames());

        return 'Ordinal [' . $ord . '] is invalid for enumeration [' . $nam
            . ']. Valid values are: ' . $validValues;
    }

}
