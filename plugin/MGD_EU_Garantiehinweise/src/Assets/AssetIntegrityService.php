<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Assets;

/** Prüft die unveränderten EU-Originaldateien anhand dokumentierter SHA-256-Werte. */
final class AssetIntegrityService
{
    public function __construct(private readonly string $assetDirectory)
    {
    }

    /** @return list<array{path:string, expected:string, actual:string, valid:bool}> */
    public function verifyAll(): array
    {
        $manifestPath = $this->assetDirectory . '/manifest.json';
        $manifest = is_file($manifestPath) ? json_decode((string)file_get_contents($manifestPath), true) : null;
        if (!is_array($manifest) || !is_array($manifest['files'] ?? null)) {
            return [['path' => 'manifest.json', 'expected' => 'gültiges Manifest', 'actual' => '', 'valid' => false]];
        }

        $results = [];
        foreach ($manifest['files'] as $entry) {
            if (!is_array($entry)) {
                continue;
            }
            $path = (string)($entry['path'] ?? '');
            $expected = strtolower((string)($entry['sha256'] ?? ''));
            $absolutePath = $this->assetDirectory . '/' . $path;
            $actual = is_file($absolutePath) ? hash_file('sha256', $absolutePath) : '';
            $results[] = [
                'path' => $path,
                'expected' => $expected,
                'actual' => $actual,
                'valid' => $path !== '' && $expected !== '' && hash_equals($expected, $actual),
            ];
        }
        return $results;
    }

    public function isValid(string $relativePath): bool
    {
        foreach ($this->verifyAll() as $result) {
            if ($result['path'] === $relativePath) {
                return $result['valid'];
            }
        }
        return false;
    }
}
