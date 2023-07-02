<?php

declare(strict_types=1);

namespace App\Application\Directory;

use Symfony\Component\HttpFoundation\Request;

class Locale implements LocaleInterface
{
    protected const DEFAULT_LOCALE = 'en_US';

    protected const ALLOWED_LOCALES = [
        self::DEFAULT_LOCALE,
        'uk_UA'
    ];

    /**
     * @var string $locale
     */
    private string $locale;

    /**
     * @param string|null $locale
     */
    public function __construct(
        string $locale = null
    ) {
        $this->locale = $locale ?: $this->retrieveLocaleFromRequest();
    }

    /**
     * @inheritDoc
     */
    public function getCurrentLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    protected function retrieveLocaleFromRequest(): string
    {
        $request = Request::createFromGlobals();
        $localeCode = $request->getPreferredLanguage();

        return in_array($localeCode, config('locale.allowed') ?? static::ALLOWED_LOCALES, true)
            ? $localeCode
            : config('locale.default') ?? static::DEFAULT_LOCALE;
    }
}
