<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Garan;

/** Liest nur die vereinbarten Funktionsattribute, ohne beliebige Artikeldaten zu übernehmen. */
final class GaranAttributeReader
{
    public const FIELDS = [
        'aktiv', 'jahre', 'marke', 'modell', 'bedingungen_url', 'hersteller', 'haltbarkeit',
        'kostenlos', 'ganzes_produkt', 'information_erhalten', 'label_geprueft', 'label_datei', 'label_sha256',
    ];

    /** @return array<string, string> Die Fachlogik erhält stets die kanonischen mgd_garan_-Namen. */
    public function read(object|array $article, string $prefix = 'mgd_garan_'): array
    {
        if (preg_match('/^[a-z][a-z0-9_]{0,38}_$/D', $prefix) !== 1) {
            return [];
        }
        $source = is_array($article)
            ? ($article['FunktionsAttribute'] ?? $article['funktionsAttribute'] ?? $article)
            : ($article->FunktionsAttribute ?? $article->funktionsAttribute ?? []);
        $source = is_array($source) || is_object($source) ? (array)$source : [];
        $values = [];
        foreach (self::FIELDS as $field) {
            $value = $source[$prefix . $field] ?? null;
            if (is_scalar($value)) {
                $values['mgd_garan_' . $field] = trim((string)$value);
            }
        }
        return $values;
    }
}
