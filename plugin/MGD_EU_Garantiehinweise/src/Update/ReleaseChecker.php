<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Update;

/**
 * Prüft ausschließlich im JTL-Backend auf öffentliche stabile Releases.
 * Es werden weder Kundendaten übertragen noch Plugin-Dateien verändert.
 * JTL führt nach einem bewussten ZIP-Upload den eigenen Update-Lebenszyklus aus.
 */
final class ReleaseChecker
{
    private const ENDPOINT = 'https://api.github.com/repos/MichaelGahnDESIGN/MGD_EU-Garantiehinweise_JTL-Plugin/releases/latest';
    private const RELEASE_PREFIX = 'https://github.com/MichaelGahnDESIGN/MGD_EU-Garantiehinweise_JTL-Plugin/releases/tag/';
    private const DOWNLOAD_PREFIX = 'https://github.com/MichaelGahnDESIGN/MGD_EU-Garantiehinweise_JTL-Plugin/releases/download/';
    private const CACHE_SECONDS = 3600;
    private const MAX_BYTES = 131072;

    /** @return array{ok: bool, update: bool, current: string, latest: ?string, release_url: ?string, download_url: ?string} */
    public function check(string $currentVersion, bool $enabled): array
    {
        $empty = ['ok' => false, 'update' => false, 'current' => $currentVersion,
            'latest' => null, 'release_url' => null, 'download_url' => null];
        if (!$enabled || preg_match('/^\d+\.\d+\.\d+$/D', $currentVersion) !== 1) {
            return $empty;
        }

        // Der Root-Hash verhindert Cache-Kollisionen mehrerer Shops auf demselben Host.
        $root = defined('PFAD_ROOT') ? (string) PFAD_ROOT : __DIR__;
        $cache = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
            . 'mgd-eu-garantie-update-' . hash('sha256', $root) . '.json';
        $stored = $this->readCache($cache);
        if ($stored !== null && ($stored['checked_at'] ?? 0) <= time()
            && time() - (int) $stored['checked_at'] < self::CACHE_SECONDS) {
            return $this->result($currentVersion, $stored['release'] ?? null);
        }

        $release = $this->fetch();
        $this->saveCache($cache, ['checked_at' => time(), 'release' => $release]);
        return $release === null ? $empty : $this->result($currentVersion, $release);
    }

    /**
     * Die Parserfunktion ist ohne Netzwerk testbar. URL und Dateiname werden
     * aus dem festen Repository und dem geprüften Tag neu aufgebaut.
     *
     * @param array<string, mixed> $data
     * @return array{tag: string, release_url: string, download_url: string}|null
     */
    public static function parseRelease(array $data): ?array
    {
        $tag = $data['tag_name'] ?? null;
        if (($data['draft'] ?? true) !== false || ($data['prerelease'] ?? true) !== false
            || !is_string($tag) || preg_match('/^v(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)$/D', $tag) !== 1
            || !is_array($data['assets'] ?? null)) {
            return null;
        }
        $version = substr($tag, 1);
        $name = 'MGD_EU-Garantiehinweise_JTL-Plugin-' . $version . '.zip';
        $url = self::DOWNLOAD_PREFIX . $tag . '/' . $name;
        foreach ($data['assets'] as $asset) {
            if (is_array($asset) && ($asset['name'] ?? null) === $name
                && ($asset['browser_download_url'] ?? null) === $url) {
                return ['tag' => $tag, 'release_url' => self::RELEASE_PREFIX . $tag, 'download_url' => $url];
            }
        }
        return null;
    }

    /** @return array{tag: string, release_url: string, download_url: string}|null */
    private function fetch(): ?array
    {
        if (!function_exists('curl_init')) {
            return null;
        }
        $body = '';
        $curl = curl_init(self::ENDPOINT);
        curl_setopt_array($curl, [
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 8,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => ['Accept: application/vnd.github+json', 'User-Agent: MGD-EU-Garantiehinweise/1.0.2'],
            CURLOPT_WRITEFUNCTION => static function ($handle, string $chunk) use (&$body): int {
                if (strlen($body) + strlen($chunk) > self::MAX_BYTES) {
                    return 0;
                }
                $body .= $chunk;
                return strlen($chunk);
            },
        ]);
        $ok = curl_exec($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($ok === false || $status !== 200) {
            return null;
        }
        $data = json_decode($body, true);
        return is_array($data) ? self::parseRelease($data) : null;
    }

    /** @param mixed $release */
    private function result(string $current, $release): array
    {
        if (!is_array($release) || !isset($release['tag'], $release['release_url'], $release['download_url'])
            || !is_string($release['tag'])
            || preg_match('/^v(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)$/D', $release['tag']) !== 1
            || $release['release_url'] !== self::RELEASE_PREFIX . $release['tag']
            || $release['download_url'] !== self::DOWNLOAD_PREFIX . $release['tag'] . '/MGD_EU-Garantiehinweise_JTL-Plugin-' . substr($release['tag'], 1) . '.zip') {
            return ['ok' => false, 'update' => false, 'current' => $current,
                'latest' => null, 'release_url' => null, 'download_url' => null];
        }
        $latest = substr($release['tag'], 1);
        return ['ok' => true, 'update' => version_compare($latest, $current, '>'),
            'current' => $current, 'latest' => $latest,
            'release_url' => $release['release_url'], 'download_url' => $release['download_url']];
    }

    /** @return array<string, mixed>|null */
    private function readCache(string $file): ?array
    {
        if (is_link($file) || !is_file($file)) {
            return null;
        }
        $raw = @file_get_contents($file);
        $data = is_string($raw) ? json_decode($raw, true) : null;
        return is_array($data) && is_int($data['checked_at'] ?? null) ? $data : null;
    }

    /** @param array<string, mixed> $data */
    private function saveCache(string $file, array $data): void
    {
        if (is_link($file)) {
            return;
        }
        $temporary = @tempnam(sys_get_temp_dir(), 'mgd-eu-update-');
        if (!is_string($temporary)) {
            return;
        }
        @chmod($temporary, 0600);
        $written = @file_put_contents($temporary, json_encode($data, JSON_UNESCAPED_SLASHES), LOCK_EX);
        if ($written === false || !@rename($temporary, $file)) {
            @unlink($temporary);
        }
    }
}
