<?php declare(strict_types=1);

/**
 * Version 2.1.1: Baukasten (Kit) im Plugin, Tabs, Bootstrap, Altcode weg, Uebernahme der alten Daten.
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

// Kurztext wörtlich in info.xml
$xml = simplexml_load_file($plugin . '/info.xml');
assert($xml !== false);
$kurz = 'Meldet sich höchstens einmal am Tag bei theme.michael-gahn.de (Plugin, Version, Shopsystem, Domain, zufällige ID). Abschaltbar in den Einstellungen.';
assert(str_contains((string) $xml->Description, $kurz));

// Tabs: Übersicht + Weitere Plugins da, Dateien vorhanden
$tabs = [];
foreach ($xml->Install->Adminmenu->Customlink as $link) {
    $tabs[(string) $link->Name] = (string) $link->Filename;
    assert(is_file($plugin . '/adminmenu/' . $link->Filename));
}
assert(($tabs['Übersicht'] ?? '') === 'uebersicht.php' && ($tabs['Weitere Plugins'] ?? '') === 'weitere-plugins.php');
foreach (['Übersicht' => 'uebersicht.php', 'Weitere Plugins' => 'weitere-plugins.php'] as $tab => $datei) {
    $code = (string) file_get_contents($plugin . '/adminmenu/' . $datei);
    assert(str_contains($code, "Setup::renderTab(KitBridge::kit(\$oPlugin), '" . $tab . "')"));
    assert(str_contains($code, 'http_response_code(403)'));
}

// Altcode ist weg (Endversion)
foreach (['src/Update', 'src/Install', 'src/Admin/UpdateBridge.php', 'adminmenu/updates.php'] as $alt) {
    assert(!file_exists($plugin . '/' . $alt), $alt);
}
// kein curl_close mehr (PHP 8.5)
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($plugin, FilesystemIterator::SKIP_DOTS));
foreach ($it as $f) {
    if ($f->isFile() && $f->getExtension() === 'php') {
        assert(!str_contains((string) file_get_contents($f->getPathname()), 'curl_close('), $f->getPathname());
    }
}

// Bootstrap: melden bei installed/enabled/updated, taeglich nur im Admin, abmelden beim Deinstallieren
$boot = (string) file_get_contents($plugin . '/Bootstrap.php');
foreach (['installed', 'enabled'] as $hook) {
    assert(preg_match('/function ' . $hook . '\([^)]*\)\s*\{[^}]*kit\(\)\?->ping\(true\)/s', $boot) === 1);
}
assert(preg_match('/function updated\([^)]*\)\s*\{[^}]*kit\(\(string\) \$newVersion\)\?->ping\(true\)/s', $boot) === 1);
assert(str_contains($boot, "if (Setup::isBackend()) {\n            \$this->kit()?->ping();"));
assert(preg_match('/function uninstalled\(bool \$deleteData = true\): void\s*\{\s*\$this->kit\(\)\?->uninstall\(\$deleteData\);\s*parent::uninstalled\(\$deleteData\);/s', $boot) === 1);
assert(!str_contains($boot, 'UpdateBridge') && !str_contains($boot, 'reportInstall'));
assert(str_contains($boot, 'Setup::forShop($this->getPlugin(), KitBridge::options($version))'));
assert(str_contains($boot, 'Setup::renderTab($this->kit(), $tabName)'));
assert(KitBridge::options() === ['legacyFolders' => ['mgd-update/eu-garantiehinweise']] && KitBridge::options('2.1.1')['version'] === '2.1.1');

// Testumgebung wie ein Shop: templates_c im Temp-Ordner
$root = sys_get_temp_dir() . '/mgd-kit-test-' . bin2hex(random_bytes(4));
$alt = $root . '/templates_c/mgd-update/eu-garantiehinweise';
$neu = $root . '/templates_c/mgd_eu_garantiehinweise_update';
mkdir($alt, 0700, true);
// Alte Daten aus 1.5.x: Meldung aus, Update-Prüfung an. Ohne Schlüssel, damit nie ein Netzaufruf nötig ist
file_put_contents($alt . '/install.json', json_encode(['report' => false]));
file_put_contents($alt . '/state.json', json_encode(['enabled' => true]));

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
                return '2.0.0';
            }
        };
    }
};
if (PFAD_ROOT === $root . '/') {
    assert(KitBridge::LEGACY_FOLDERS === ['mgd-update/eu-garantiehinweise']);
    $kit = KitBridge::kit($fake);
    assert($kit instanceof JtlKit && $kit->product === 'mgd-eu-garantiehinweise' && $kit->version === '2.0.0');
    // alte Daten wurden übernommen, alt stillgelegt, nichts gelöscht
    assert(json_decode((string) file_get_contents($neu . '/install.json'), true) === ['report' => false]);
    assert(json_decode((string) file_get_contents($neu . '/state.json'), true) === ['enabled' => true]);
    assert(is_file($neu . '/.htaccess') && !is_file($alt . '/install.json') && is_file($alt . '/install.json.migrated') && is_file($alt . '/state.json.migrated'));
    $ic = $kit->installClient();
    assert($ic !== null && $ic->isEnabled() === false && $ic->status() === 'Meldung ist ausgeschaltet.');

    // updated($old, $new): die neue Version gewinnt
    assert(KitBridge::kit($fake, '2.1.1')?->version === '2.1.1');

    // ohne Backend-Recht nur die Fehlermeldung, nie ein Netzaufruf
    $uebersicht = Setup::renderTab($kit, 'Übersicht');
    assert(is_string($uebersicht) && !str_contains($uebersicht, '<form'));
    $weitere = Setup::renderTab($kit, 'Weitere Plugins');
    assert(is_string($weitere) && str_contains($weitere, 'Berechtigung'));
    assert(Setup::renderTab($kit, 'Anzeige') === null);

    // Deinstallieren ohne Schlüssel: kein Netz. Daten behalten > Ordner bleibt, Daten löschen > Ordner weg
    assert($kit->uninstall(false) === true && is_dir($neu));
    assert($kit->uninstall(true) === true && !is_dir($neu));
}

// nur im Admin melden (nicht CLI, nicht Frontend)
assert(Setup::isBackend() === false);

// Aufraeumen
exec('rm -rf ' . escapeshellarg($root));
