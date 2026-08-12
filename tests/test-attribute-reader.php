<?php declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use Plugin\MGD_EU_Garantiehinweise\Garan\GaranAttributeReader;

$werte = [
    'mgd_garan_aktiv' => '1',
    'mgd_garan_jahre' => '3',
    'mgd_garan_marke' => 'Beispielmarke',
    'mgd_garan_modell' => 'Modell 100',
    'mgd_garan_bedingungen_url' => 'https://example.org/garantie',
];

$reader = new GaranAttributeReader();
assert($reader->read($werte) === $werte);
assert($reader->read(['FunktionsAttribute' => $werte]) === $werte);
assert($reader->read((object)['FunktionsAttribute' => (object)$werte]) === $werte);
