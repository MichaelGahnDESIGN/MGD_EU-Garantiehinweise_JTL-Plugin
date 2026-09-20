<?php declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use Plugin\MGD_EU_Garantiehinweise\Frontend\HtmlRenderer;
use Plugin\MGD_EU_Garantiehinweise\Garan\GaranEligibilityResult;
use Plugin\MGD_EU_Garantiehinweise\Configuration\Settings;

$renderer = new HtmlRenderer();
$notice = $renderer->legalNotice('de', '/asset.svg', 'product');
assert(str_contains($notice, 'Gewährleistungsrechte'));
assert(str_contains($notice, '<img'));
assert(str_contains($notice, 'Offizieller EU-Hinweis'));

$result = new GaranEligibilityResult(true, [], [
    'duration' => 3.0,
    'brand' => '<script>Marke</script>',
    'model' => 'Modell 1',
    'terms_url' => 'https://example.org/bedingungen',
]);
$label = $renderer->garanLabel('de', '/garan-kompakt.svg', '/garan-voll.svg', $result, new Settings(['garan_display' => 'dialog']));
assert(str_contains($label, '3 Jahre'));
assert(!str_contains($label, '<script>'));
assert(str_contains($label, '&lt;script&gt;'));
assert(str_contains($label, 'aria-controls="mgd-garan-dialog"'));
assert(str_contains($label, 'aria-expanded="false"'));
assert(str_contains($label, '/garan-kompakt.svg'));
assert(str_contains($label, '/garan-voll.svg'));

$inline = $renderer->garanLabel('en', '/compact.svg', '/full.png', $result);
assert(!str_contains($inline, '<dialog'));
assert(str_contains($inline, '/full.png'));
assert(!str_contains($inline, '/compact.svg'));
assert(!str_contains($notice, '<dialog'));
assert(!str_contains($notice, 'data-mgd-dialog-open'));
assert(str_contains($notice, 'mgd-width-800'));
assert(str_contains($notice, 'https://europa.eu/youreurope/garantien'));
assert($renderer->garanLabel('de', '', '', new GaranEligibilityResult(false, ['inactive'], [])) === '');
