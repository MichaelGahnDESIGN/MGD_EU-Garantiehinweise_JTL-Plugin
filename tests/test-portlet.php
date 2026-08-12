<?php declare(strict_types=1);

$wurzel = dirname(__DIR__) . '/plugin/MGD_EU_Garantiehinweise/Portlets/EUGarantiehinweise';
$klasse = file_get_contents($wurzel . '/EUGarantiehinweise.php');
$template = file_get_contents($wurzel . '/EUGarantiehinweise.tpl');

assert(is_string($klasse) && str_contains($klasse, 'final class EUGarantiehinweise extends Portlet'));
assert(str_contains($klasse, 'InputType::RICHTEXT'));
assert(is_string($template) && str_contains($template, "|escape:'html'"));
assert(str_contains($template, 'aria-labelledby'));
assert(str_contains($klasse, 'normalizeInternalPath'));
assert(str_contains($klasse, "str_starts_with(\$path, '//')"));
assert(str_contains($template, '$instance->safeLinkUrl'));
assert(!str_contains($template, "href=\"{\$instance->getProperty('link-url')"));
