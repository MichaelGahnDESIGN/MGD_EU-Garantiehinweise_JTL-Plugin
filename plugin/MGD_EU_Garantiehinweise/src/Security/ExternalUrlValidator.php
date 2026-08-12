<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Security;

/**
 * Erlaubt ausschließlich absolute HTTPS-Links ohne eingebettete Zugangsdaten.
 */
final class ExternalUrlValidator
{
    public function isValid(string $url): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        $teile = parse_url($url);
        return is_array($teile)
            && strtolower((string)($teile['scheme'] ?? '')) === 'https'
            && isset($teile['host'])
            && !isset($teile['user'])
            && !isset($teile['pass']);
    }
}

