<?php

/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: WeakSubjectTrait.php
 * Class name...: WeakSubjectTrait.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-11-13 00:38:53
 */
declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums\helpers;

if ((PHP_MAJOR_VERSION < 8)) {
    exit('Trait [WeakSubjectTrait] is available for PHP >=8.0.0');
}

use SplObserver;
use WeakMap;


/**
 * Trait SplSubjectTrait
 */
trait WeakSubjectTrait /* implements SplSubject */
{
    /**
     * @var WeakMap
     */
    private WeakMap $observers;

    /**
     * @param SplObserver $observer
     */
    public function attach(SplObserver $observer): void
    {
        if (empty($this->observers)) {
            $this->observers = new WeakMap();
        }
        $this->observers[$observer] = true;
    }

    /**
     * @param SplObserver $observer
     */
    public function detach(SplObserver $observer): void
    {
        if (!empty($this->observers)) {
            unset($this->observers[$observer]);
        }
    }

    public function notify(): void
    {
        if (!empty($this->observers)) {
            foreach ($this->observers as $observer) {
                $observer->update($this);
            }
        }
    }

}