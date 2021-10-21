<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: InvalidEnumNameException.php
 * Class name...: InvalidEnumNameException.php
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
 * Class InvalidEnumNameException
 *
 * @package iomywiab\iomywiab_php_enums\exceptions
 */
class InvalidEnumNameException extends LogicException implements EnumException
{
    /**
     * InvalidEnumNameException constructor.
     *
     * @param EnumDefinitionInterface|null $enum
     * @param string|null                  $name
     * @param Throwable|null               $previous
     */
    public function __construct(
        ?EnumDefinitionInterface $enum,
        ?string $name,
        ?Throwable $previous = null
    ) {
        parent::__construct(self::createMessage($enum, $name), EnumExceptionCode::INVALID_NAME, $previous);
    }

    /**
     * @param EnumDefinitionInterface|null $enum
     * @param string|null                  $name
     * @return string
     */
    private static function createMessage(
        ?EnumDefinitionInterface $enum,
        ?string $name
    ): string {
        $cls = Format::toShortClassName($enum);
        $nam = Format::toString($name);
        $validValues = Format::toValueList($enum === null ? [] : $enum->getAllNames());

        return 'Name [' . $nam . '] is invalid for enumeration [' . $cls .
            ']. Valid values are: ' . $validValues;
    }

}
