<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: InvalidEnumDefinitionException.php
 * Class name...: InvalidEnumDefinitionException.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-20 20:38:50
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums\exceptions;

use LogicException;
use iomywiab\iomywiab_php_constraints\Format;
use iomywiab\iomywiab_php_enums\enums\EnumExceptionCode;
use Throwable;

/**
 * Class InvalidEnumNameException
 *
 * @package iomywiab\iomywiab_php_enums\exceptions
 */
class InvalidEnumDefinitionException extends LogicException implements EnumException
{
    /**
     * InvalidEnumNameException constructor.
     *
     * @param string|null    $classname
     * @param string|null    $message
     * @param Throwable|null $previous
     */
    public function __construct(
        ?string $classname,
        ?string $message,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            self::createMessage($classname, $message),
            EnumExceptionCode::INVALID_DEFINITION,
            $previous
        );
    }

    /**
     * @param string|null $classname
     * @param string|null $message
     * @return string
     */
    private static function createMessage(
        ?string $classname,
        ?string $message
    ): string {
        $cls = Format::toString($classname);
        $msg = Format::toString($message);

        return 'Invalid definition for enum class [' . $cls . ']: ' . $msg;
    }

}
