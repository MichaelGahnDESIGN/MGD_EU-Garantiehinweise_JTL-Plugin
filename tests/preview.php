<?php
// Nur lokale synthetische Darstellungsprobe; niemals Bestandteil des Releasepakets.
require __DIR__ . '/bootstrap.php';
use Plugin\MGD_EU_Garantiehinweise\Frontend\HtmlRenderer;
use Plugin\MGD_EU_Garantiehinweise\Configuration\Settings;
use Plugin\MGD_EU_Garantiehinweise\Garan\GaranEligibilityResult;
$language = ($_GET['language'] ?? '') === 'en' ? 'en' : 'de';
$base = '/plugin/MGD_EU_Garantiehinweise/';
$renderer = new HtmlRenderer();
?><!doctype html><html lang="<?= $language ?>"><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Lokale Labelprobe – kein JTL-Shop</title><link rel="stylesheet" href="<?= $base ?>frontend/css/frontend.css"><style>body{font:16px/1.5 Arial;margin:0;padding:16px}main{max-width:1000px;margin:auto}</style><main><h1>Lokale Darstellungsprobe</h1><p>Synthetische Testansicht, keine JTL-Abnahme. Das GARAN-Muster unten ist keine Produktkennzeichnung.</p>
<?= $renderer->legalNotice($language, $base . 'assets/eu/' . $language . '/legal-guarantee-notice.svg', 'checkout') ?>
<button id="order">Test-Bestellbutton</button><h2>GARAN-Muster: nur Dialogtest</h2>
<?= $renderer->garanLabel($language, $base.'assets/eu/garan/garan-label-nested-display.svg', $base.'assets/eu/garan/garan-label-colour.svg', new GaranEligibilityResult(true, [], ['duration'=>3, 'brand'=>'Testmarke', 'model'=>'Testmodell', 'terms_url'=>'https://example.org/garantie']), new Settings(['garan_display'=>'dialog'])) ?>
</main><script src="<?= $base ?>frontend/js/dialog.js" defer></script></html>
