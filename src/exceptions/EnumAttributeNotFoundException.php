<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: EnumAttributeNotFoundException.php
 * Class name...: EnumAttributeNotFoundException.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-20 20:38:50
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
class EnumAttributeNotFoundException extends LogicException implements EnumException, NotFoundExceptionInterface
{

    /**
     * UnsupportedEnumException constructor.
     *
     * @param EnumDefinitionInterface|null $definition
     * @param mixed                        $value
     * @param null|string                  $comment
     * @param Throwable|null               $previous
     */
    public function __construct(
        ?EnumDefinitionInterface $definition,
        $value,
        ?string $comment = null,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            static::createMessage($definition, $value, $comment),
            EnumExceptionCode::ATTRIBUTE_NOT_FOUND,
            $previous
        );
    }

    /**
     * @param mixed                        $value
     * @param EnumDefinitionInterface|null $definition
     * @param null|string                  $comment
     *
     * @return string
     */
    private static function createMessage(
        ?EnumDefinitionInterface $definition,
        $value,
        ?string $comment
    ): string {
        $description = Format::toDescription($value);
        $name = $definition === null ? 'n/a' : $definition->getEnumClassName();

        $message = 'Attribute name [' . $description . '] not found in enum [' . $name . ']';

        if (!empty($comment)) {
            $message .= ': ';
            $message .= $comment;
        }
        return $message;
    }

}
