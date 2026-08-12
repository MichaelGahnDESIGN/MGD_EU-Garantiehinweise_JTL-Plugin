<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Garan;

use Plugin\MGD_EU_Garantiehinweise\Security\ExternalUrlValidator;

/**
 * Prüft konservativ, ob alle Voraussetzungen für das GARAN-Label vorliegen.
 */
final class GaranEligibilityService
{
    public function __construct(private readonly ?ExternalUrlValidator $urlValidator = null)
    {
    }

    /** @param array<string, mixed> $attributes */
    public function evaluate(array $attributes): GaranEligibilityResult
    {
        $errors   = [];
        $active   = trim((string)($attributes['mgd_garan_aktiv'] ?? ''));
        $duration = trim((string)($attributes['mgd_garan_jahre'] ?? ''));
        $brand    = trim((string)($attributes['mgd_garan_marke'] ?? ''));
        $model    = trim((string)($attributes['mgd_garan_modell'] ?? ''));
        $url      = trim((string)($attributes['mgd_garan_bedingungen_url'] ?? ''));

        if ($active !== '1') {
            $errors[] = 'inactive';
        }
        if (!is_numeric($duration) || (float)$duration <= 2.0) {
            $errors[] = 'duration_invalid';
        }
        if ($brand === '') {
            $errors[] = 'brand_missing';
        }
        if ($model === '') {
            $errors[] = 'model_missing';
        }

        $validator = $this->urlValidator ?? new ExternalUrlValidator();
        if (!$validator->isValid($url)) {
            $errors[] = 'terms_url_invalid';
        }

        return new GaranEligibilityResult($errors === [], $errors, [
            'duration' => is_numeric($duration) ? (float)$duration : 0.0,
            'brand' => $brand,
            'model' => $model,
            'terms_url' => $url,
        ]);
    }
}

