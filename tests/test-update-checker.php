<?php declare(strict_types=1);

require_once __DIR__ . '/../plugin/MGD_EU_Garantiehinweise/src/Update/ReleaseChecker.php';

use Plugin\MGD_EU_Garantiehinweise\Update\ReleaseChecker;

$tag = 'v1.0.1';
$url = 'https://github.com/MichaelGahnDESIGN/MGD_EU-Garantiehinweise_JTL-Plugin/releases/download/'
    . $tag . '/MGD_EU-Garantiehinweise_JTL-Plugin-1.0.1.zip';
$release = [
    'tag_name' => $tag,
    'draft' => false,
    'prerelease' => false,
    'assets' => [['name' => 'MGD_EU-Garantiehinweise_JTL-Plugin-1.0.1.zip', 'browser_download_url' => $url]],
];
$parsed = ReleaseChecker::parseRelease($release);
assert($parsed !== null);
assert($parsed['download_url'] === $url);

foreach ([
    ['draft' => true],
    ['prerelease' => true],
    ['tag_name' => 'v1.0.1-beta'],
    ['assets' => [['name' => 'MGD_EU-Garantiehinweise_JTL-Plugin-1.0.1.zip', 'browser_download_url' => 'https://example.org/fremd.zip']]],
    ['assets' => []],
] as $change) {
    assert(ReleaseChecker::parseRelease(array_replace($release, $change)) === null);
}
