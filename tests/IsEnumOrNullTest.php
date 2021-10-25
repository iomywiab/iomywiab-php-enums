<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: IsEnumOrNullTest.php
 * Class name...: IsEnumOrNullTest.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-21 07:18:23
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums_tests;

require_once __DIR__ . '/test.php';

use Exception;
use iomywiab\iomywiab_php_constraints\exceptions\ConstraintViolationException;
use iomywiab\iomywiab_php_constraints_tests\ConstraintTestCase;
use iomywiab\iomywiab_php_enums\constraints\IsEnumOrNull;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class IsEnumTest
 *
 * @package iomywiab\iomywiab_php_enums_tests\enums
 */
class IsEnumOrNullTest extends ConstraintTestCase
{

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ConstraintViolationException
     * @noinspection PhpUndefinedMethodInspection
     */
    public function testIsValid(): void
    {
        $this->checkConstraint(
            new IsEnumOrNull(TestValue::class),
            [
                TestValue::ONE,
                TestValue::Two,
                TestValue::three,
                TestValue::FOUR,
                TestValue::Five,
                TestValue::ONE()->getName(),
                TestValue::Two()->getName(),
                TestValue::three()->getName(),
                TestValue::FOUR()->getName(),
                TestValue::Five()->getName(),
                TestValue::ONE(),
                TestValue::Two(),
                TestValue::three(),
                TestValue::FOUR(),
                TestValue::Five(),
                null
            ],
            [666]
        );
    }

    /**
     * @throws ConstraintViolationException
     * @throws Exception
     * @noinspection PhpUndefinedMethodInspection
     */
    public function testAssert(): void
    {
        self::expectException(ConstraintViolationException::class);
        try {
            IsEnumOrNull::assert(TestValue::class, null);
            IsEnumOrNull::assert(TestValue::class, TestValue::ONE);
            IsEnumOrNull::assert(TestValue::class, TestValue::Two);
            IsEnumOrNull::assert(TestValue::class, TestValue::three);
            IsEnumOrNull::assert(TestValue::class, TestValue::FOUR);
            IsEnumOrNull::assert(TestValue::class, TestValue::Five);
            IsEnumOrNull::assert(TestValue::class, TestValue::ONE()->getName());
            IsEnumOrNull::assert(TestValue::class, TestValue::Two()->getName());
            IsEnumOrNull::assert(TestValue::class, TestValue::three()->getName());
            IsEnumOrNull::assert(TestValue::class, TestValue::FOUR()->getName());
            IsEnumOrNull::assert(TestValue::class, TestValue::Five()->getName());
            IsEnumOrNull::assert(TestValue::class, TestValue::ONE());
            IsEnumOrNull::assert(TestValue::class, TestValue::Two());
            IsEnumOrNull::assert(TestValue::class, TestValue::three());
            IsEnumOrNull::assert(TestValue::class, TestValue::FOUR());
            IsEnumOrNull::assert(TestValue::class, TestValue::Five());
        } catch (Exception $cause) {
            throw new Exception('Unexpected exception', 0, $cause);
        }
        IsEnumOrNull::assert(TestValue::class, '123');
    }
}
