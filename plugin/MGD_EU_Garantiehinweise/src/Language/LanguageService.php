<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Language;

/**
 * Begrenzt die Ausgabe bewusst auf die mitgelieferten Sprachen Deutsch/Englisch.
 */
final class LanguageService
{
    private string $fallback;

    public function __construct(string $fallback = 'de')
    {
        $this->fallback = strtolower($fallback) === 'en' ? 'en' : 'de';
    }

    public function resolve(string $locale): string
    {
        $sprache = strtolower(substr(trim($locale), 0, 2));
        return in_array($sprache, ['de', 'en'], true) ? $sprache : $this->fallback;
    }
}

