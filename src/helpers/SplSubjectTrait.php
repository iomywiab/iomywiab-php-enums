<?php
/*
 * This file is part of the iomywiab-php-enums package.
 *
 * Copyright (c) 2012-2021 Patrick Nehls <iomywiab@premium-postfach.de>, Tornesch, Germany.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * File name....: SplSubjectTrait.php
 * Class name...: SplSubjectTrait.php
 * Project name.: iomywiab-php-enums
 * Module name..: iomywiab-php-enums
 * Last modified: 2021-12-06 16:58:22
 */
declare(strict_types=1);

namespace iomywiab\iomywiab_php_enums\helpers;

use SplObjectStorage;
use SplObserver;

/**
 * Attention! Usage of trait might lead to memory leaks ig observer references subject.
 * If you are on PHP >= 8.0 use WeakSubjectTrait instead!
 * Trait SplSubjectTrait
 */
trait SplSubjectTrait /* implements SplSubject */
{
    /**
     * @var SplObjectStorage|null
     */
    private $observers;

    /**
     * @param SplObserver $observer
     */
    public function attach(SplObserver $observer): void
    {
        if (!isset($this->observers)) {
            $this->observers = new SplObjectStorage();
        }
        $this->observers->attach($observer);
    }

    /**
     * @param SplObserver $observer
     */
    public function detach(SplObserver $observer): void
    {
        if (isset($this->observers)) {
            $this->observers->detach($observer);
            if (0 === $this->observers->count()) {
                unset($this->observers);
            }
        }
    }

    public function notify(): void
    {
        if (isset($this->observers)) {
            foreach ($this->observers as $observer) {
                $observer->update($this);
            }
        }
    }

}