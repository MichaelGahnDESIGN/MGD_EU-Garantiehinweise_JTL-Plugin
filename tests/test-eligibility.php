<?php declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use Plugin\MGD_EU_Garantiehinweise\Garan\GaranEligibilityService;

$dienst = new GaranEligibilityService();
$basis  = [
    'mgd_garan_aktiv' => '1',
    'mgd_garan_jahre' => '3',
    'mgd_garan_marke' => 'Beispielmarke',
    'mgd_garan_modell' => 'Modell 100',
    'mgd_garan_bedingungen_url' => 'https://example.org/garantie',
];

assert($dienst->evaluate($basis)->isEligible());
assert($dienst->evaluate($basis)->errors() === []);

$faelle = [
    ['mgd_garan_aktiv', '0', 'inactive'],
    ['mgd_garan_jahre', '2', 'duration_invalid'],
    ['mgd_garan_jahre', '2.5', null],
    ['mgd_garan_marke', '', 'brand_missing'],
    ['mgd_garan_modell', '', 'model_missing'],
    ['mgd_garan_bedingungen_url', 'http://example.org/garantie', 'terms_url_invalid'],
];

foreach ($faelle as [$feld, $wert, $erwarteterFehler]) {
    $daten        = $basis;
    $daten[$feld] = $wert;
    $ergebnis     = $dienst->evaluate($daten);

    if ($erwarteterFehler === null) {
        assert($ergebnis->isEligible());
        continue;
    }

    assert(!$ergebnis->isEligible());
    assert(in_array($erwarteterFehler, $ergebnis->errors(), true));
}

