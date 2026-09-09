<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Garan;

use Plugin\MGD_EU_Garantiehinweise\Security\ExternalUrlValidator;

/** Prüft jede GARAN-Voraussetzung; fehlende Angaben erlauben niemals eine Kennzeichnung. */
final class GaranEligibilityService
{
    public function __construct(private readonly ?ExternalUrlValidator $urlValidator = null)
    {
    }

    /** @param array<string, mixed> $attributes Normalisierte Wawi-Namen, unabhängig vom konfigurierten Präfix. */
    public function evaluate(array $attributes): GaranEligibilityResult
    {
        $read = static fn(string $key): string => is_scalar($attributes['mgd_garan_' . $key] ?? null)
            ? trim((string)$attributes['mgd_garan_' . $key]) : '';
        $errors = [];
        if ($read('aktiv') !== '1') {
            $errors[] = 'inactive';
        }
        foreach (['hersteller', 'haltbarkeit', 'kostenlos', 'ganzes_produkt', 'information_erhalten', 'label_geprueft'] as $key) {
            if ($read($key) !== '1') {
                $errors[] = 'confirmation_' . $key . '_missing';
            }
        }
        // Keine Rundung: ausschließlich volle oder halbe Jahre in begrenzter Darstellungsspanne.
        $duration = str_replace(',', '.', $read('jahre'));
        if (preg_match('/^[0-9]{1,2}(?:\.[05])?$/D', $duration) !== 1 || (float)$duration <= 2) {
            $errors[] = 'duration_invalid';
        }
        $brand = $read('marke');
        $model = $read('modell');
        if ($brand === '' || strlen($brand) > 400) {
            $errors[] = 'brand_missing';
        }
        if ($model === '' || strlen($model) > 400) {
            $errors[] = 'model_missing';
        }
        $url = $read('bedingungen_url');
        if (!(($this->urlValidator ?? new ExternalUrlValidator())->isValid($url))) {
            $errors[] = 'terms_url_invalid';
        }
        $filename = $read('label_datei');
        $hash = $read('label_sha256');
        if (preg_match('/^[a-zA-Z0-9][a-zA-Z0-9_-]{0,119}\.(?:png|jpg|jpeg)$/D', $filename) !== 1) {
            $errors[] = 'label_filename_invalid';
        }
        if (preg_match('/^[a-f0-9]{64}$/D', $hash) !== 1) {
            $errors[] = 'label_hash_invalid';
        }
        return new GaranEligibilityResult($errors === [], $errors, [
            'duration' => is_numeric($duration) ? (float)$duration : 0.0,
            'brand' => $brand, 'model' => $model, 'terms_url' => $url,
            'label_file' => $filename, 'label_hash' => $hash,
        ]);
    }
}
