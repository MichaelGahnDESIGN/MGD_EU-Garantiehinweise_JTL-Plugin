<?php declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use Plugin\MGD_EU_Garantiehinweise\Garan\GaranEligibilityService;

$dienst = new GaranEligibilityService();
$basis  = [
    'mgd_garan_aktiv' => '1',
    'mgd_garan_hersteller' => '1',
    'mgd_garan_haltbarkeit' => '1',
    'mgd_garan_kostenlos' => '1',
    'mgd_garan_ganzes_produkt' => '1',
    'mgd_garan_information_erhalten' => '1',
    'mgd_garan_label_geprueft' => '1',
    'mgd_garan_label_datei' => 'modell-100.png',
    'mgd_garan_label_sha256' => str_repeat('a', 64),
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


// Jede fachliche Bestätigung und fehlerhafte Datentypen müssen einzeln sperren.
foreach (['hersteller', 'haltbarkeit', 'kostenlos', 'ganzes_produkt', 'information_erhalten', 'label_geprueft'] as $confirmation) {
    $invalid = $basis; unset($invalid['mgd_garan_' . $confirmation]);
    assert(!$dienst->evaluate($invalid)->isEligible());
}
foreach (['2.1', '3.1', '3e0', 'INF', [], '100'] as $duration) {
    $invalid = $basis; $invalid['mgd_garan_jahre'] = $duration;
    assert(!$dienst->evaluate($invalid)->isEligible());
}
$half = $basis; $half['mgd_garan_jahre'] = '2,5';
assert($dienst->evaluate($half)->isEligible());
