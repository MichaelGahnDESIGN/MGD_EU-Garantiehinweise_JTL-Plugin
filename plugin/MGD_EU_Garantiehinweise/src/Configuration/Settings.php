<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Configuration;

use Plugin\MGD_EU_Garantiehinweise\Language\LanguageService;

/** Validiert native JTL-Einstellungen, bevor diese Darstellung oder Selektoren beeinflussen. */
final class Settings
{
    /** @var array<string, string> */
    private array $values = [];

    /** @param array<string, mixed> $values Rohdaten werden nie unmittelbar ausgegeben. */
    public function __construct(array $values = [])
    {
        foreach (self::definitions() as $key => $definition) {
            $value = is_string($values[$key] ?? null) ? trim($values[$key]) : $definition['default'];
            $valid = $definition['options'] !== []
                ? array_key_exists($value, $definition['options'])
                : ($key === 'attribute_prefix'
                    ? preg_match('/^[a-z][a-z0-9_]{0,38}_$/D', $value) === 1
                    : preg_match('/^[#.][A-Za-z_][A-Za-z0-9_-]{0,79}$/D', $value) === 1);
            $this->values[$key] = $valid ? $value : $definition['default'];
        }
    }

    /** JTL übernimmt Speicherung, Berechtigungen und CSRF-Schutz seiner eigenen Einstellungsmaske. */
    public static function fromPlugin(object $plugin): self
    {
        $values = [];
        foreach (self::definitions() as $key => $definition) {
            $values[$key] = $plugin->getConfig()->getValue('mgd_eu_' . $key);
        }
        return new self($values);
    }

    /** @return array<string, array{name:string,default:string,options:array,group:string,description:string}> */
    public static function definitions(): array
    {
        static $definitions;
        if ($definitions === null) {
            $definitions = [];
            foreach (glob(__DIR__ . '/Definitions/*.php') ?: [] as $file) {
                $definitions[basename($file, '.php')] = require $file;
            }
        }
        return $definitions;
    }

    /** Unbekannte Schlüssel sind Programmierfehler und dürfen nicht still einen unsicheren Wert liefern. */
    public function get(string $key): string
    {
        return $this->values[$key] ?? throw new \InvalidArgumentException('Unbekannte Plugin-Einstellung.');
    }

    public function noticeEnabled(string $context): bool
    {
        return in_array($context, ['product', 'cart', 'checkout'], true)
            && $this->get('notice_enabled') === 'Y' && $this->get('notice_' . $context) === 'Y';
    }

    public function language(string $shopLanguage): string
    {
        return $this->get('language') === 'auto'
            ? (new LanguageService($this->get('fallback_language')))->resolve($shopLanguage)
            : $this->get('language');
    }
}
