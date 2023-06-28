<?php

declare(strict_types=1);

namespace App\Application\Directory;

interface LocaleInterface
{
    /**
     * Returns current locale code
     *
     * @return string
     */
    public function getCurrentLocale(): string;
}
