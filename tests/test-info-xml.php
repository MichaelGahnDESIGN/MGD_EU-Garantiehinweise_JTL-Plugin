<?php declare(strict_types=1);

/**
 * Prüft die zentralen Metadaten des JTL-Plugins.
 *
 * Der Test verwendet absichtlich nur PHP-Bordmittel. So lässt sich das
 * Plugin bereits vor einer JTL-Installation und ohne Composer überprüfen.
 */
$datei = __DIR__ . '/../plugin/MGD_EU_Garantiehinweise/info.xml';
$xml   = simplexml_load_file($datei);

assert($xml !== false);
assert((string)$xml->PluginID === 'MGD_EU_Garantiehinweise');
assert((string)$xml->MinShopVersion === '5.5.0');
assert((string)$xml->Version === '1.0.0');
assert((string)$xml->Author === 'Michael Gahn DESIGN');
assert((string)$xml->URL === 'https://Michael-Gahn.de');
assert(!isset($xml->Install->CSS));
assert(!isset($xml->Install->JS));
