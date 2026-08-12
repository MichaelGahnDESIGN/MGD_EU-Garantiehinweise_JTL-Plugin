<?php declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use Plugin\MGD_EU_Garantiehinweise\Assets\AssetIntegrityService;

$directory = __DIR__ . '/../plugin/MGD_EU_Garantiehinweise/assets/eu';
$manifest = json_decode((string)file_get_contents($directory . '/manifest.json'), true);
assert(is_array($manifest));
assert(count($manifest['files'] ?? []) === 4);

foreach ($manifest['files'] as $entry) {
    assert(isset($entry['path'], $entry['type'], $entry['language'], $entry['sha256'], $entry['source_url']));
    assert(str_starts_with($entry['source_url'], 'https://commission.europa.eu/'));
}

$service = new AssetIntegrityService($directory);
foreach ($service->verifyAll() as $result) {
    assert($result['valid'] === true, 'Ungültige EU-Datei: ' . $result['path']);
}
