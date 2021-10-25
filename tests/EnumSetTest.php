<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: EnumSetTest.php
 * Class name...: EnumSetTest.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-21 07:18:23
 */

/** @noinspection PhpUndefinedMethodInspection */

declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums_tests;

require_once __DIR__ . '/test.php';

use iomywiab\iomywiab_php_enums\EnumDefinition;
use iomywiab\iomywiab_php_enums\enums\EnumFormat;
use iomywiab\iomywiab_php_enums\EnumSet;
use iomywiab\iomywiab_php_enums\exceptions\EnumException;
use iomywiab\iomywiab_php_enums\interfaces\EnumSetInterface;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * Class EnumDefinition
 *
 * @package iomywiab\iomywiab_php_enums_tests\enums
 */
class EnumSetTest extends TestCase
{

    /**
     * @throws ExpectationFailedException
     * @throws EnumException
     */
    public function testValue(): void
    {
        $set = new EnumSet(TestValue::class);
        self::assertFalse($set->getDefinition()->isBitmaskEnum());
        $this->checkSet(TestValue::class, $set, [], []);
        $this->checkSet(TestValue::class, $set->copy(), [], []);

        $set->add(TestValue::ONE);
        $this->checkSet(TestValue::class, $set, [TestValue::ONE], ['ONE']);
        $this->checkSet(TestValue::class, $set->copy(), [TestValue::ONE], ['ONE']);

        $set->add(TestValue::Two());
        $this->checkSet(TestValue::class, $set, [TestValue::ONE, TestValue::Two], ['ONE', 'Two']);
        $this->checkSet(TestValue::class, $set->copy(), [TestValue::ONE, TestValue::Two], ['ONE', 'Two']);

        $set->add(TestValue::three()->getName());
        $this->checkSet(
            TestValue::class,
            $set,
            [TestValue::ONE, TestValue::Two, TestValue::three],
            ['ONE', 'Two', 'three']
        );
        $this->checkSet(
            TestValue::class,
            $set->copy(),
            [TestValue::ONE, TestValue::Two, TestValue::three],
            ['ONE', 'Two', 'three']
        );

        $set->add([TestValue::FOUR, TestValue::Five()->getName()]);
        $this->checkSet(
            TestValue::class,
            $set,
            [TestValue::ONE, TestValue::Two, TestValue::three, TestValue::FOUR, TestValue::Five],
            ['ONE', 'Two', 'three', 'FOUR', 'Five']
        );
        $this->checkSet(
            TestValue::class,
            $set->copy(),
            [TestValue::ONE, TestValue::Two, TestValue::three, TestValue::FOUR, TestValue::Five],
            ['ONE', 'Two', 'three', 'FOUR', 'Five']
        );

        $set->add([TestValue::FOUR, TestValue::Five()->getName()]);
        $this->checkSet(
            TestValue::class,
            $set,
            [TestValue::ONE, TestValue::Two, TestValue::three, TestValue::FOUR, TestValue::Five],
            ['ONE', 'Two', 'three', 'FOUR', 'Five']
        );
        $this->checkSet(
            TestValue::class,
            $set->copy(),
            [TestValue::ONE, TestValue::Two, TestValue::three, TestValue::FOUR, TestValue::Five],
            ['ONE', 'Two', 'three', 'FOUR', 'Five']
        );

        $set->remove(TestValue::ONE);
        $this->checkSet(
            TestValue::class,
            $set,
            [TestValue::Two, TestValue::three, TestValue::FOUR, TestValue::Five],
            ['Two', 'three', 'FOUR', 'Five']
        );
        $this->checkSet(
            TestValue::class,
            $set->copy(),
            [TestValue::Two, TestValue::three, TestValue::FOUR, TestValue::Five],
            ['Two', 'three', 'FOUR', 'Five']
        );

        $set->remove(TestValue::Two());
        $this->checkSet(
            TestValue::class,
            $set,
            [TestValue::three, TestValue::FOUR, TestValue::Five],
            ['three', 'FOUR', 'Five']
        );
        $this->checkSet(
            TestValue::class,
            $set->copy(),
            [TestValue::three, TestValue::FOUR, TestValue::Five],
            ['three', 'FOUR', 'Five']
        );

        $set->remove(TestValue::three()->getName());
        $this->checkSet(TestValue::class, $set, [TestValue::FOUR, TestValue::Five], ['FOUR', 'Five']);
        $this->checkSet(TestValue::class, $set->copy(), [TestValue::FOUR, TestValue::Five], ['FOUR', 'Five']);

        $set->remove([TestValue::FOUR, TestValue::Five()->getName()]);
        $this->checkSet(TestValue::class, $set, [], []);
        $this->checkSet(TestValue::class, $set->copy(), [], []);

        $set->add([TestValue::FOUR, TestValue::Five()->getName()]);
        $this->checkSet(TestValue::class, $set, [TestValue::FOUR, TestValue::Five], ['FOUR', 'Five']);
        $this->checkSet(TestValue::class, $set->copy(), [TestValue::FOUR, TestValue::Five], ['FOUR', 'Five']);

        $set->clear();
        $this->checkSet(TestValue::class, $set, [], []);
        $this->checkSet(TestValue::class, $set->copy(), [], []);

        $set1 = (new EnumSet(TestValue::class))->add([TestValue::ONE, TestValue::Two]);
        $set2 = (new EnumSet(TestValue::class))->add([TestValue::three, TestValue::Two]);
        self::assertEquals([TestValue::ONE, TestValue::three], $set1->diff($set2)->toArray(EnumFormat::ORDINAL));
        self::assertEquals([TestValue::Two], $set1->intersect($set2)->toArray(EnumFormat::ORDINAL));
        self::assertEquals(
            [TestValue::ONE, TestValue::Two, TestValue::three],
            $set1->merge($set2)->toArray(EnumFormat::ORDINAL)
        );
    }

    /**
     * @param string           $valueClass
     * @param EnumSetInterface $set
     * @param array            $ordinals
     * @param array            $names
     * @throws ExpectationFailedException
     * @throws EnumException
     */
    private function checkSet(string $valueClass, EnumSetInterface $set, array $ordinals, array $names): void
    {
        self::assertSameSize($ordinals, $names);

        $definition = EnumDefinition::getInstance($valueClass);
        self::assertEquals($definition, $set->getDefinition());

        self::assertEquals(count($ordinals), $set->count());
        self::assertEquals(0 == count($ordinals), $set->isEmpty());

        foreach ($definition->getAllOrdinals() as $ordinal) {
            self::assertEquals(in_array($ordinal, $ordinals), $set->contains($ordinal));
            self::assertEquals(in_array($ordinal, $ordinals), $set->contains($definition->getEnum($ordinal)));
        }
        foreach ($definition->getAllNames() as $name) {
            self::assertEquals(in_array($name, $names), $set->contains($name));
        }

        self::assertEquals($names, $set->toArray());
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        self::assertEquals($names, $set->toArray(EnumFormat::NAME));
        self::assertEquals($ordinals, $set->toArray(EnumFormat::ORDINAL));

        self::assertEquals(implode(',', $names), $set->toString());
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        self::assertEquals(implode('|', $names), $set->toString('|', EnumFormat::NAME));
        self::assertEquals(implode(',', $ordinals), $set->toString(',', EnumFormat::ORDINAL));
    }

    /**
     * @throws ExpectationFailedException
     * @throws EnumException
     */
    public function testBitmask(): void
    {
        $set = new EnumSet(TestBitmask::class);
        self::assertTrue($set->getDefinition()->isBitmaskEnum());
        $this->checkSet(TestBitmask::class, $set, [], []);
        $this->checkSet(TestBitmask::class, $set->copy(), [], []);

        $set->add(TestBitmask::ONE);
        $this->checkSet(TestBitmask::class, $set, [TestBitmask::ONE], ['one']);
        $this->checkSet(TestBitmask::class, $set->copy(), [TestBitmask::ONE], ['one']);

        $set->add(TestBitmask::TWO());
        $this->checkSet(TestBitmask::class, $set, [TestBitmask::ONE, TestBitmask::TWO], ['one', 'two']);
        $this->checkSet(TestBitmask::class, $set->copy(), [TestBitmask::ONE, TestBitmask::TWO], ['one', 'two']);

        $set->add(TestBitmask::three()->getName());
        $this->checkSet(
            TestBitmask::class,
            $set,
            [TestBitmask::ONE, TestBitmask::TWO, TestBitmask::THREE],
            ['one', 'two', 'three']
        );
        $this->checkSet(
            TestBitmask::class,
            $set->copy(),
            [TestBitmask::ONE, TestBitmask::TWO, TestBitmask::THREE],
            ['one', 'two', 'three']
        );

        $set->add([TestBitmask::FOUR, TestBitmask::Five()->getName()]);
        $this->checkSet(
            TestBitmask::class,
            $set,
            [TestBitmask::ONE, TestBitmask::TWO, TestBitmask::THREE, TestBitmask::FOUR, TestBitmask::FIVE],
            ['one', 'two', 'three', 'four', 'five']
        );
        $this->checkSet(
            TestBitmask::class,
            $set->copy(),
            [TestBitmask::ONE, TestBitmask::TWO, TestBitmask::THREE, TestBitmask::FOUR, TestBitmask::FIVE],
            ['one', 'two', 'three', 'four', 'five']
        );

        $set->add([TestBitmask::FOUR, TestBitmask::Five()->getName()]);
        $this->checkSet(
            TestBitmask::class,
            $set,
            [TestBitmask::ONE, TestBitmask::TWO, TestBitmask::THREE, TestBitmask::FOUR, TestBitmask::FIVE],
            ['one', 'two', 'three', 'four', 'five']
        );
        $this->checkSet(
            TestBitmask::class,
            $set->copy(),
            [TestBitmask::ONE, TestBitmask::TWO, TestBitmask::THREE, TestBitmask::FOUR, TestBitmask::FIVE],
            ['one', 'two', 'three', 'four', 'five']
        );

        $set->remove(TestBitmask::ONE);
        $this->checkSet(
            TestBitmask::class,
            $set,
            [TestBitmask::TWO, TestBitmask::THREE, TestBitmask::FOUR, TestBitmask::FIVE],
            ['two', 'three', 'four', 'five']
        );
        $this->checkSet(
            TestBitmask::class,
            $set->copy(),
            [TestBitmask::TWO, TestBitmask::THREE, TestBitmask::FOUR, TestBitmask::FIVE],
            ['two', 'three', 'four', 'five']
        );

        $set->remove(TestBitmask::TWO());
        $this->checkSet(
            TestBitmask::class,
            $set,
            [TestBitmask::THREE, TestBitmask::FOUR, TestBitmask::FIVE],
            ['three', 'four', 'five']
        );
        $this->checkSet(
            TestBitmask::class,
            $set->copy(),
            [TestBitmask::THREE, TestBitmask::FOUR, TestBitmask::FIVE],
            ['three', 'four', 'five']
        );

        $set->remove(TestBitmask::THREE()->getName());
        $this->checkSet(TestBitmask::class, $set, [TestBitmask::FOUR, TestBitmask::FIVE], ['four', 'five']);
        $this->checkSet(TestBitmask::class, $set->copy(), [TestBitmask::FOUR, TestBitmask::FIVE], ['four', 'five']);

        $set->remove([TestBitmask::FOUR, TestBitmask::FIVE()->getName()]);
        $this->checkSet(TestBitmask::class, $set, [], []);
        $this->checkSet(TestBitmask::class, $set->copy(), [], []);

        $set->add([TestBitmask::FOUR, TestBitmask::FIVE()->getName()]);
        $this->checkSet(TestBitmask::class, $set, [TestBitmask::FOUR, TestBitmask::FIVE], ['four', 'five']);
        $this->checkSet(TestBitmask::class, $set->copy(), [TestBitmask::FOUR, TestBitmask::FIVE], ['four', 'five']);

        $set->clear();
        $this->checkSet(TestBitmask::class, $set, [], []);
        $this->checkSet(TestBitmask::class, $set->copy(), [], []);

        $set1 = (new EnumSet(TestBitmask::class))->add([TestBitmask::ONE, TestBitmask::TWO]);
        $set2 = (new EnumSet(TestBitmask::class))->add([TestBitmask::THREE, TestBitmask::TWO]);
        self::assertEquals([TestBitmask::ONE, TestBitmask::THREE], $set1->diff($set2)->toArray(EnumFormat::ORDINAL));
        self::assertEquals([TestBitmask::TWO], $set1->intersect($set2)->toArray(EnumFormat::ORDINAL));
        self::assertEquals(
            [TestBitmask::ONE, TestBitmask::TWO, TestBitmask::THREE],
            $set1->merge($set2)->toArray(EnumFormat::ORDINAL)
        );
    }

}
