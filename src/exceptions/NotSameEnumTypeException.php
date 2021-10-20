<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: NotSameEnumTypeException.php
 * Class name...: NotSameEnumTypeException.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-20 20:38:50
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums\exceptions;

use LogicException;
use iomywiab\iomywiab_php_enums\enums\EnumExceptionCode;
use iomywiab\iomywiab_php_enums\interfaces\EnumDefinitionInterface;
use Throwable;

/**
 * Class InvalidEnumOrdinalException
 *
 * @package iomywiab\iomywiab_php_enums\exceptions
 */
class NotSameEnumTypeException extends LogicException implements EnumException
{

    /**
     * InvalidEnumOrdinalException constructor.
     * @param EnumDefinitionInterface|null $thisEnum
     * @param EnumDefinitionInterface|null $otherEnum
     * @param Throwable|null               $previous
     */
    public function __construct(
        ?EnumDefinitionInterface $thisEnum,
        ?EnumDefinitionInterface $otherEnum,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            self::createMessage($thisEnum, $otherEnum),
            EnumExceptionCode::NOT_SAME_ENUM_TYPE,
            $previous
        );
    }

    /**
     * @param EnumDefinitionInterface|null $thisEnum
     * @param EnumDefinitionInterface|null $otherEnum
     * @return string
     */
    private static function createMessage(
        ?EnumDefinitionInterface $thisEnum,
        ?EnumDefinitionInterface $otherEnum
    ): string {
        $thisType = $thisEnum ? $thisEnum->getEnumClassName() : 'n/a';
        $otherType = $otherEnum ? $otherEnum->getEnumClassName() : 'n/a';

        return 'Enums are of different type. this=[' . $thisType . ']. other=[' . $otherType . '].';
    }

}
