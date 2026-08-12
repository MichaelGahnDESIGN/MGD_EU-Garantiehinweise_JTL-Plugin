<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Garan;

/**
 * Unveränderliches Prüfergebnis für ein mögliches GARAN-Label.
 */
final class GaranEligibilityResult
{
    /** @param list<string> $errors @param array<string, string|float> $data */
    public function __construct(
        private readonly bool $eligible,
        private readonly array $errors,
        private readonly array $data
    ) {
    }

    public function isEligible(): bool
    {
        return $this->eligible;
    }

    /** @return list<string> */
    public function errors(): array
    {
        return $this->errors;
    }

    /** @return array<string, string|float> */
    public function data(): array
    {
        return $this->data;
    }
}

