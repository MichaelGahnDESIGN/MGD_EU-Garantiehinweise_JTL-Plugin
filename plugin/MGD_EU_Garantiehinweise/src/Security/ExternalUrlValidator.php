<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Security;

/** Erlaubt öffentliche HTTPS-Informationslinks ohne Zugangsdaten oder tokenhaltige Querystrings. */
final class ExternalUrlValidator
{
    public function isValid(string $url): bool
    {
        if (strlen($url) > 2048 || preg_match('/[\x00-\x20\x7f\\\\]/', $url)
            || filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }
        $parts = parse_url($url);
        if (!is_array($parts) || strtolower($parts['scheme'] ?? '') !== 'https'
            || !isset($parts['host']) || isset($parts['user']) || isset($parts['pass'])
            || isset($parts['query']) || isset($parts['fragment'])
            || (isset($parts['port']) && $parts['port'] !== 443)) {
            return false;
        }
        $host = strtolower(trim($parts['host'], '[]'));
        // Nur öffentliche DNS-Namen: keine IP-Adressen, localhost oder interne Namensräume.
        return str_contains($host, '.') && !filter_var($host, FILTER_VALIDATE_IP)
            && !preg_match('/^[0-9.]+$/D', $host)
            && !str_ends_with($host, '.')
            && !preg_match('/\.(?:local|localhost|internal|lan|home)$/D', $host);
    }
}
