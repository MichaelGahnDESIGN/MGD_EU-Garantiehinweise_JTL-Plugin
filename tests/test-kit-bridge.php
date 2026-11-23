<?php declare(strict_types=1);

/**
 * Brueckenversion 1.6.0: Baukasten (Kit) im Plugin, Tabs, Bootstrap, Uebernahme der alten Daten.
 * Laeuft ohne JTL und ohne Netz. Es gibt hier nie einen echten Aufruf zur Plattform.
 */
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../plugin/MGD_EU_Garantiehinweise/src/Admin/KitBridge.php';

use Plugin\MGD_EU_Garantiehinweise\Admin\KitBridge;
use Plugin\MGD_EU_Garantiehinweise\Kit\Platform\Jtl\Config;
use Plugin\MGD_EU_Garantiehinweise\Kit\Platform\Jtl\JtlKit;
use Plugin\MGD_EU_Garantiehinweise\Kit\Platform\Jtl\Setup;

$plugin = dirname(__DIR__) . '/plugin/MGD_EU_Garantiehinweise';

// Werte vom Generator
assert(Config::PRODUCT === 'mgd-eu-garantiehinweise' && Config::PLUGIN_ID === 'MGD_EU_Garantiehinweise');
assert(Config::NAME === 'MGD EU-Garantiehinweise' && Config::CATALOG_SLUG === 'plugin-eu-garantiehinweise-jtl');
assert(Config::REPO === 'MGD_EU-Garantiehinweise_JTL-Plugin');

// info.xml: neue Tabs da, alter Updates-Tab weg
$xml = simplexml_load_file($plugin . '/info.xml');
assert($xml !== false);
$tabs = [];
foreach ($xml->Install->Adminmenu->Customlink as $link) {
    $tabs[(string) $link->Name] = (string) $link->Filename;
    assert(is_file($plugin . '/adminmenu/' . $link->Filename));
}
assert(($tabs['Übersicht'] ?? '') === 'uebersicht.php' && ($tabs['Weitere Plugins'] ?? '') === 'weitere-plugins.php');
assert(!in_array('updates.php', $tabs, true));
// Namen genau wie in der Anleitung, sonst erkennt der Baukasten den Tab nicht
foreach (['Übersicht' => 'uebersicht.php', 'Weitere Plugins' => 'weitere-plugins.php'] as $tab => $datei) {
    $code = (string) file_get_contents($plugin . '/adminmenu/' . $datei);
    assert(str_contains($code, "Setup::renderTab(KitBridge::kit(\$oPlugin), '" . $tab . "')"));
    assert(str_contains($code, 'http_response_code(403)'));
}

// Bootstrap: melden bei installed/enabled/updated, taeglich nur ausserhalb vom Frontend, abmelden beim Deinstallieren
$boot = (string) file_get_contents($plugin . '/Bootstrap.php');
foreach (['installed', 'enabled', 'updated'] as $hook) {
    assert(preg_match('/function ' . $hook . '\([^)]*\)\s*\{[^}]*kit\(\)\?->ping\(true\)/s', $boot) === 1);
}
assert(str_contains($boot, "if (!Shop::isFrontend() && PHP_SAPI !== 'cli') {\n            \$this->kit()?->ping();"));
assert(preg_match('/function uninstalled\(bool \$deleteData = true\): void\s*\{\s*\$this->kit\(\)\?->uninstall\(\$deleteData\);\s*parent::uninstalled\(\$deleteData\);/s', $boot) === 1);
// alter Weg wird nicht mehr angemeldet
assert(!str_contains($boot, 'UpdateBridge') && !str_contains($boot, 'reportInstall'));

// Version nur an einer Stelle gepflegt: kein fester Wert im Kit
assert(!str_contains((string) file_get_contents($plugin . '/Kit/Platform/Jtl/Setup.php'), '1.6.0'));

// Testumgebung wie ein Shop: templates_c im Temp-Ordner
$root = sys_get_temp_dir() . '/mgd-kit-test-' . bin2hex(random_bytes(4));
mkdir($root . '/templates_c/mgd-update/eu-garantiehinweise', 0700, true);
$compiled = $root . '/templates_c';
$alt = $compiled . '/' . KitBridge::OLD_FOLDER;
$neu = $compiled . '/' . KitBridge::NEW_FOLDER;

// Nichts da > nichts uebernommen
assert(KitBridge::adoptOld($root . '/gibts-nicht') === []);
assert(KitBridge::adoptOld($compiled) === []);

// Alte Daten aus 1.5.x: Meldung aus, Update-Pruefung an. Ohne Schluessel, damit nie ein Netzaufruf nötig ist
file_put_contents($alt . '/install.json', json_encode(['report' => false]));
file_put_contents($alt . '/state.json', json_encode(['enabled' => true]));
$done = KitBridge::adoptOld($compiled);
sort($done);
assert($done === ['install.json', 'state.json']);
assert(is_file($neu . '/install.json') && is_file($neu . '/state.json') && is_file($neu . '/.htaccess'));
assert(json_decode((string) file_get_contents($neu . '/install.json'), true) === ['report' => false]);
assert(json_decode((string) file_get_contents($neu . '/state.json'), true) === ['enabled' => true]);
// alt stillgelegt, nichts geloescht
assert(!is_file($alt . '/install.json') && is_file($alt . '/install.json.migrated') && is_file($alt . '/state.json.migrated'));
// zweiter Lauf ueberschreibt nichts
file_put_contents($neu . '/install.json', json_encode(['report' => true]));
assert(KitBridge::adoptOld($compiled) === []);
assert(json_decode((string) file_get_contents($neu . '/install.json'), true) === ['report' => true]);

// kaputtes JSON, zu gross und Symlink werden nicht uebernommen
unlink($neu . '/install.json');
unlink($neu . '/state.json');
file_put_contents($alt . '/install.json', '{kaputt');
file_put_contents($alt . '/state.json', str_repeat('x', KitBridge::MAX_BYTES + 1));
assert(KitBridge::adoptOld($compiled) === [] && !is_file($neu . '/install.json') && !is_file($neu . '/state.json'));
unlink($alt . '/install.json');
unlink($alt . '/state.json');
file_put_contents($root . '/ziel.json', json_encode(['report' => true]));
symlink($root . '/ziel.json', $alt . '/install.json');
assert(KitBridge::adoptOld($compiled) === [] && !is_file($neu . '/install.json'));
unlink($alt . '/install.json');

// Kit aus einem JTL-Plugin-Objekt (Fake), Datenordner und Produkt stimmen
if (!defined('PFAD_ROOT')) {
    define('PFAD_ROOT', $root . '/');
    define('PFAD_COMPILEDIR', 'templates_c/');
}
if (!defined('URL_SHOP')) {
    define('URL_SHOP', 'https://shop.example.test');
}
$fake = new class($plugin) {
    public function __construct(private readonly string $base)
    {
    }

    public function getPaths(): object
    {
        return new class($this->base) {
            public function __construct(private readonly string $b)
            {
            }

            public function getBasePath(): string
            {
                return $this->b;
            }
        };
    }

    public function getMeta(): object
    {
        return new class {
            public function getVersion(): string
            {
                return '1.6.0';
            }
        };
    }
};
if (PFAD_ROOT === $root . '/') {
    // alte Daten ohne Schluessel, Meldung ausgeschaltet
    file_put_contents($alt . '/install.json', json_encode(['report' => false]));
    $kit = KitBridge::kit($fake);
    assert($kit instanceof JtlKit);
    $ic = $kit->installClient();
    assert($ic !== null && $ic->product() === 'mgd-eu-garantiehinweise');
    assert($ic->isEnabled() === false && $ic->status() === 'Meldung ist ausgeschaltet.');
    assert(is_file($neu . '/install.json') && is_file($neu . '/.htaccess'));

    // ohne Backend-Recht nur die Fehlermeldung, nie ein Netzaufruf
    $uebersicht = Setup::renderTab($kit, 'Übersicht');
    assert(is_string($uebersicht) && !str_contains($uebersicht, '<form'));
    $weitere = Setup::renderTab($kit, 'Weitere Plugins');
    assert(is_string($weitere) && str_contains($weitere, 'Berechtigung'));
    // fremde Tabs gehen uns nichts an
    assert(Setup::renderTab($kit, 'Anzeige') === null);

    // Deinstallieren ohne Schluessel: kein Netz. Daten behalten > Ordner bleibt, Daten loeschen > Ordner weg
    assert($kit->uninstall(false) === true && is_dir($neu));
    assert($kit->uninstall(true) === true && !is_dir($neu));
}

// Aufraeumen
exec('rm -rf ' . escapeshellarg($root));
