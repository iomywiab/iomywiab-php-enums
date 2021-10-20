<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: EnumDefinitionTest.php
 * Class name...: EnumDefinitionTest.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-20 20:41:20
 */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums_tests;

require_once __DIR__ . '/test.php';

use iomywiab\iomywiab_php_enums\EnumDefinition;
use iomywiab\iomywiab_php_enums\enums\EnumFormat;
use PHPUnit\Framework\TestCase;

/**
 * Class EnumDefinition
 *
 * @package iomywiab\iomywiab_php_enums_tests\enums
 */
class EnumDefinitionTest extends TestCase
{

    /**
     * @param EnumDefinition $definition
     * @param array          $values
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \iomywiab\iomywiab_php_enums\exceptions\EnumException
     */
    protected function checkDefinition(
        EnumDefinition $definition,
        array $values
    ) {
        self::assertGreaterThanOrEqual(1, count($values));
        self::assertSameSize($values, $definition->getAllOrdinals());
        self::assertSameSize($values, $definition->getAllNames());

        self::assertFalse($definition->has(-123123123));
        self::assertFalse($definition->has('XXX'));
        foreach ($values as $ordinal => $name) {
            self::assertTrue($definition->has($ordinal));
            self::assertTrue($definition->hasOrdinal($ordinal));
//            self::assertFalse($definition->hasName($ordinal));
            self::assertTrue($definition->has($name));
//            self::assertFalse($definition->hasOrdinal($name));
            self::assertTrue($definition->hasName($name));

            self::assertEquals($ordinal, $definition->getOrdinal($ordinal));
            self::assertEquals($name, $definition->getName($ordinal));
            self::assertEquals($ordinal, $definition->getOrdinal($name));
            self::assertEquals($name, $definition->getName($name));

            $enum = $definition->getEnum($ordinal);
            self::assertEquals($definition, $enum->getDefinition());
            self::assertEquals($ordinal, $enum->getOrdinal());
            self::assertEquals($name, $enum->getName());
            self::assertEquals($ordinal, $enum->ordinal);
            self::assertEquals($name, $enum->name);

            $enum = $definition->getEnum($name);
            self::assertEquals($definition, $enum->getDefinition());
            self::assertEquals($ordinal, $enum->getOrdinal());
            self::assertEquals($name, $enum->getName());
            self::assertEquals($ordinal, $enum->ordinal);
            self::assertEquals($name, $enum->name);
        }

        self::assertEquals(array_keys($values), array_values($definition->getAllOrdinals()));
        self::assertEquals(array_values($values), array_values($definition->getAllNames()));

        $count = 0;
        foreach ($definition as $ordinal => $enum) {
            $count++;
            self::assertEquals($ordinal, $enum->getOrdinal());
            self::assertEquals($ordinal, $enum->ordinal);
            self::assertArrayHasKey($ordinal, $values);
            self::assertTrue(in_array($enum->getName(), $values));
        }
        self::assertCount($count, $values);

        self::assertEquals($values, $definition->toArray());
        self::assertEquals(array_keys($values), $definition->toArray(EnumFormat::ORDINAL));
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        self::assertEquals($values, $definition->toArray(EnumFormat::NAME));

        self::assertEquals(implode(',', $values), $definition->toString());
        self::assertEquals(implode('|', array_keys($values)), $definition->toString('|', EnumFormat::ORDINAL));
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        self::assertEquals(implode(',', $values), $definition->toString(',', EnumFormat::NAME));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \iomywiab\iomywiab_php_enums\exceptions\EnumException
     */
    public function testValid()
    {
        $values = [
            TestValue::ONE => 'ONE',
            TestValue::Two => 'Two',
            TestValue::three => 'three',
            TestValue::FOUR => 'FOUR',
            TestValue::Five => 'Five'
        ];
        $definition = EnumDefinition::getInstance(TestValue::class);

        $this->checkDefinition($definition, $values);
    }

}
