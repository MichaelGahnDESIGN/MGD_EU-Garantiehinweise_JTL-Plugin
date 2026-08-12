<?php declare(strict_types=1);

$wurzel = dirname(__DIR__);
$readme = file_get_contents($wurzel . '/README.md');
$wawi = file_get_contents($wurzel . '/docs/JTL-WAWI-ATTRIBUTE.md');
$recht = file_get_contents($wurzel . '/docs/RECHTLICHE-ABGRENZUNG.md');

assert(is_string($readme) && str_contains($readme, 'GPL-3.0-or-later'));
assert(str_contains($readme, 'Copyright (c) 2026 Michael Gahn DESIGN - https://Michael-Gahn.de'));
assert(str_contains($readme, 'Deutsch und Englisch'));
assert(is_string($recht) && str_contains($recht, 'keine Rechtsberatung'));

foreach (['mgd_garan_aktiv', 'mgd_garan_jahre', 'mgd_garan_marke', 'mgd_garan_modell', 'mgd_garan_bedingungen_url'] as $attribut) {
    assert(is_string($wawi) && str_contains($wawi, $attribut));
}
