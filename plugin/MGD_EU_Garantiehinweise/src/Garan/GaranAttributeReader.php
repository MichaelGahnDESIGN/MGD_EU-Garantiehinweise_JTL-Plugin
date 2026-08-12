<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Garan;

/**
 * Liest die fünf vereinbarten JTL-Wawi-Funktionsattribute aus Artikeldaten.
 */
final class GaranAttributeReader
{
    private const NAMES = [
        'mgd_garan_aktiv',
        'mgd_garan_jahre',
        'mgd_garan_marke',
        'mgd_garan_modell',
        'mgd_garan_bedingungen_url',
    ];

    /** @return array<string, string> */
    public function read(object|array $article): array
    {
        if (is_array($article)) {
            $quelle = $article['FunktionsAttribute']
                ?? $article['funktionsAttribute']
                ?? $article;
        } else {
            $quelle = $article->FunktionsAttribute
                ?? $article->funktionsAttribute
                ?? [];
        }

        $quelle = is_object($quelle) ? (array)$quelle : (array)$quelle;
        $werte = [];

        foreach (self::NAMES as $name) {
            $werte[$name] = trim((string)($quelle[$name] ?? ''));
        }

        return $werte;
    }
}
