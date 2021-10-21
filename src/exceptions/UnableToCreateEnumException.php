<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: UnableToCreateEnumException.php
 * Class name...: UnableToCreateEnumException.php
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
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

/**
 * Class UnsupportedEnumException
 *
 * @package iomywiab\iomywiab_php_enums\exceptions
 */
class UnableToCreateEnumException extends LogicException implements EnumException, NotFoundExceptionInterface
{

    /**
     * UnsupportedEnumException constructor.
     *
     * @param EnumDefinitionInterface|null $definition
     * @param mixed                        $value
     * @param Throwable|null               $previous
     */
    public function __construct(
        ?EnumDefinitionInterface $definition,
        $value,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            self::createMessage($definition, $value),
            EnumExceptionCode::UNABLE_TO_CREATE_ENUM,
            $previous
        );
    }

    /**
     * @param mixed                        $value
     * @param EnumDefinitionInterface|null $definition
     *
     * @return string
     */
    private static function createMessage(
        ?EnumDefinitionInterface $definition,
        $value
    ): string {
        $value = Format::toString($value);
        $name = $definition === null ? 'n/a' : $definition->getEnumClassName();
        return 'Unable to create enum of type [' . $name . '] for value [' . $value . ']';
    }

}
