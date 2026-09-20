<?php declare(strict_types=1);

$version = '1.1.0';
$wurzel = dirname(__DIR__);
$archiv = $wurzel . '/dist/MGD_EU-Garantiehinweise_JTL-Plugin-' . $version . '.zip';

// Immer frisch bauen: Ein altes ZIP darf geänderten Quellcode nicht fälschlich bestätigen.
passthru('bash ' . escapeshellarg($wurzel . '/scripts/build-release.sh') . ' ' . escapeshellarg($version), $status);
assert($status === 0);

$ausgabe = shell_exec('unzip -Z1 ' . escapeshellarg($archiv));
assert(is_string($ausgabe));
$dateien = array_values(array_filter(explode("\n", trim($ausgabe))));
assert($dateien !== []);

foreach ($dateien as $datei) {
    assert(str_starts_with($datei, 'MGD_EU_Garantiehinweise/'));
    assert(!str_contains($datei, '/tests/'));
    assert(!str_contains($datei, '/.git/'));
    assert(!str_contains($datei, 'zauberwort'));
}

foreach (['MGD_EU_Garantiehinweise/info.xml', 'MGD_EU_Garantiehinweise/Bootstrap.php', 'MGD_EU_Garantiehinweise/assets/eu/manifest.json', 'MGD_EU_Garantiehinweise/Portlets/EUGarantiehinweise/EUGarantiehinweise.php'] as $pflichtdatei) {
    assert(in_array($pflichtdatei, $dateien, true));
}

foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($wurzel . '/plugin/MGD_EU_Garantiehinweise', FilesystemIterator::SKIP_DOTS)) as $source) {
    if (!$source->isFile() || $source->getFilename() === '.DS_Store') { continue; }
    $relative = substr($source->getPathname(), strlen($wurzel . '/plugin/'));
    $packaged = shell_exec('unzip -p ' . escapeshellarg($archiv) . ' ' . escapeshellarg($relative));
    assert(is_string($packaged) && hash('sha256', $packaged) === hash_file('sha256', $source->getPathname()), $relative);
}
