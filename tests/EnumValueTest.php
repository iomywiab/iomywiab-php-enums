<?php /*
 * @copyright     Copyright (c) 2012-2021 iomywiab\iomywiab Media Germany GmbH,
 *                Hamburg, Germany. All rights reserved.
 * @link          http://iomywiab\iomywiab.com iomywiab\iomywiab.com
 * File name....: EnumValueTest.php
 * File path....: C:/_GitLocal/iomywiab\iomywiab-php-enums/tests/EnumValueTest.php
 * Class name...: EnumValueTest.php
 * Project name.: iomywiab\iomywiab-php-enums
 * Module name..: iomywiab\iomywiab-php-enums
 * Last modified: 2021-02-12 13:36:49
 */ /*
 * @copyright     Copyright (c) 2012-2021 iomywiab\iomywiab Media Germany GmbH,
 *                Hamburg, Germany. All rights reserved.
 * @link          http://iomywiab\iomywiab.com iomywiab\iomywiab.com
 * File name....: EnumValueTest.php
 * File path....: C:/_GitLocal/iomywiab\iomywiab-php-enums/tests/EnumValueTest.php
 * Class name...: EnumValueTest.php
 * Project name.: iomywiab\iomywiab-php-enums
 * Module name..: iomywiab\iomywiab-php-enums
 * Last modified: 2021-04-12 12:06:53
 */ /*
 * @copyright     Copyright (c) 2012-2021 iomywiab\iomywiab Media Germany GmbH,
 *                Hamburg, Germany. All rights reserved.
 * @link          http://iomywiab\iomywiab.com iomywiab\iomywiab.com
 * File name....: EnumValueTest.php
 * File path....: C:/_GitLocal/iomywiab\iomywiab-php-enums/tests/EnumValueTest.php
 * Class name...: EnumValueTest.php
 * Project name.: iomywiab\iomywiab-php-enums
 * Module name..: iomywiab\iomywiab-php-enums
 * Last modified: 2021-04-12 13:15:52
 */ /*
 * @copyright     Copyright (c) 2012-2021 iomywiab\iomywiab Media Germany GmbH,
 *                Hamburg, Germany. All rights reserved.
 * @link          http://iomywiab\iomywiab.com iomywiab\iomywiab.com
 * File name....: EnumValueTest.php
 * File path....: C:/_GitLocal/iomywiab\iomywiab-php-enums/tests/EnumValueTest.php
 * Class name...: EnumValueTest.php
 * Project name.: iomywiab\iomywiab-php-enums
 * Module name..: iomywiab\iomywiab-php-enums
 * Last modified: 2021-05-04 17:13:11
 */ /*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: EnumValueTest.php
 * Class name...: EnumValueTest.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-21 07:18:23
 */
/** @noinspection PhpUndefinedMethodInspection */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums_tests;

require_once __DIR__ . '/test.php';

use iomywiab\iomywiab_php_enums\exceptions\EnumAttributeNotFoundException;
use iomywiab\iomywiab_php_enums\exceptions\EnumException;
use PHPUnit\Framework\TestCase;

/**
 * Class EnumDefinition
 *
 * @package iomywiab\iomywiab_php_enums_tests\enums
 */
class EnumValueTest extends TestCase
{
    /**
     * @param int    $ordinal
     * @param string $name
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \iomywiab\iomywiab_php_enums\exceptions\EnumException
     */
    protected function check(int $ordinal, string $name): void
    {
        $enum = new TestValue($ordinal);
        self::assertNotNull($enum->getDefinition());
        self::assertEquals($ordinal, $enum->getOrdinal());
        self::assertEquals($name, $enum->getName());
        self::assertEquals($ordinal, $enum->getOrdinal());
        self::assertEquals($name, $enum->name);

        $enum = new TestValue($name);
        self::assertNotNull($enum->getDefinition());
        self::assertEquals($ordinal, $enum->getOrdinal());
        self::assertEquals($name, $enum->getName());
        self::assertEquals($ordinal, $enum->getOrdinal());
        self::assertEquals($name, $enum->name);

        $enum = TestValue::$name();
        self::assertNotNull($enum->getDefinition());
        self::assertEquals($ordinal, $enum->getOrdinal());
        self::assertEquals($name, $enum->getName());
        self::assertEquals($ordinal, $enum->ordinal);
        self::assertEquals($name, $enum->name);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \iomywiab\iomywiab_php_enums\exceptions\EnumException
     */
    public function testValid()
    {
        $this->check(111, 'ONE');
        $this->check(222, 'Two');
        $this->check(333, 'three');
        $this->check(444, 'FOUR');
        $this->check(555, 'Five');

        $one = TestValue::ONE();
        self::assertTrue($one->isONE());
        self::assertFalse($one->isTwo());
        self::assertFalse($one->isthree());
        self::assertFalse($one->isFOUR());
        self::assertFalse($one->isFive());

        $two = TestValue::Two();
        self::assertFalse($two->isONE());
        self::assertTrue($two->isTwo());
        self::assertFalse($two->isthree());
        self::assertFalse($two->isFOUR());
        self::assertFalse($two->isFive());
    }

    public function testDuplicate()
    {
        $this->expectException(EnumException::class);
        TestDuplicate::ONE();
    }

    /**
     * @throws EnumException
     */
    public function testEmpty()
    {
        $this->expectException(EnumException::class);
        new TestEmpty(1);
    }

    public function testFloat()
    {
        $this->expectException(EnumException::class);
        TestOrdinalFloat::ONE();
    }

    public function testString() {
        $this->expectException(EnumException::class);
        TestOrdinalString::ONE();
    }

    public function testBool() {
        $this->expectException(EnumException::class);
        TestOrdinalBool::ONE();
    }

    public function testNUll()
    {
        $this->expectException(EnumException::class);
        TestOrdinalNull::ONE();
    }

    public function testArray()
    {
        $this->expectException(EnumException::class);
        TestOrdinalArray::ONE();
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testAttributes()
    {
        $one = TestValue::ONE();
        $two = TestValue::Two();
        $three = TestValue::three();
        $four = TestValue::FOUR();
        $five = TestValue::Five();

        $def1 = TestValue::definition();
        $def2 = $one->getDefinition();
        self::assertEquals($def1, $def2);

        self::assertEquals('Eins', $one->getDISPLAY());
        self::assertNull($two->getDISPLAY());
        self::assertNull($three->getDISPLAY());
        self::assertEquals('Vier', $four->getDISPLAY());
        self::assertNull($five->getDISPLAY());

        self::assertEquals(1, $one->getSecondaryKey());
        self::assertNull($two->getSecondaryKey());
        self::assertNull($three->getSecondaryKey());
        self::assertNull($four->getSecondaryKey());
        self::assertEquals(5, $five->getSecondaryKey());

        self::assertEquals(111111, $one->gettEST());
        self::assertNull($two->gettEST());
        self::assertEquals(333333, $three->gettEST());
        self::assertNull($four->gettEST());
        self::assertNull($five->gettEST());

        $this->expectException(EnumAttributeNotFoundException::class);
        self::assertNull($five->getTest());
    }

}
