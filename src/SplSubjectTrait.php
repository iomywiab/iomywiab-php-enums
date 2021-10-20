<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: SplObserverTrait.php
 * Class name...: SplObserverTrait.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-10-20 20:52:13
 */
declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums;

use SplObjectStorage;
use SplObserver;

/**
 * Trait SplSubjectTrait
 */
trait SplSubjectTrait
{
    /**
     * @var SplObjectStorage
     */
    private $observers;

    /**
     * @param SplObserver $observer
     */
    public function attach(SplObserver $observer): void
    {
        if (empty($this->observers)) {
            $this->observers = new SplObjectStorage();
        }
        $this->observers->attach($observer);
    }

    /**
     * @param SplObserver $observer
     */
    public function detach(SplObserver $observer): void
    {
        if (!empty($this->observers)) {
            $this->observers->detach($observer);
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