<?php declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use Plugin\MGD_EU_Garantiehinweise\Frontend\HtmlRenderer;
use Plugin\MGD_EU_Garantiehinweise\Garan\GaranEligibilityResult;

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
$label = $renderer->garanLabel('de', '/garan-kompakt.svg', '/garan-voll.svg', $result);
assert(str_contains($label, '3 Jahre'));
assert(!str_contains($label, '<script>'));
assert(str_contains($label, '&lt;script&gt;'));
assert(str_contains($label, 'aria-controls="mgd-garan-dialog"'));
assert(str_contains($label, 'aria-expanded="false"'));
assert(str_contains($label, '/garan-kompakt.svg'));
assert(str_contains($label, '/garan-voll.svg'));
