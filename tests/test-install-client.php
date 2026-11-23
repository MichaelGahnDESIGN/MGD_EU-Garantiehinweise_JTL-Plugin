<?php declare(strict_types=1);

/**
 * Free-Lizenz Meldung: Schalter, Hinweis, kein Netz im Test, Kurztext in info.xml.
 * Es wird nie ping() aufgerufen, damit der Test offline bleibt.
 */
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../plugin/MGD_EU_Garantiehinweise/src/Admin/UpdateBridge.php';

use Plugin\MGD_EU_Garantiehinweise\Admin\UpdateBridge;
use Plugin\MGD_EU_Garantiehinweise\Install\InstallClient;
use Plugin\MGD_EU_Garantiehinweise\Install\Settings;
use Plugin\MGD_EU_Garantiehinweise\Update\Admin\UpdatesPage;
use Plugin\MGD_EU_Garantiehinweise\Update\ClientConfig;
use Plugin\MGD_EU_Garantiehinweise\Update\Crypto\KeyRing;
use Plugin\MGD_EU_Garantiehinweise\Update\Http\{HttpClient, HttpResponse};
use Plugin\MGD_EU_Garantiehinweise\Update\License\NullLicenseProvider;
use Plugin\MGD_EU_Garantiehinweise\Update\Store\JsonFileStateStore;
use Plugin\MGD_EU_Garantiehinweise\Update\UpdateClient;

$plugin = dirname(__DIR__) . '/plugin/MGD_EU_Garantiehinweise';

// Kurztext wörtlich in info.xml
$kurz = 'Meldet sich höchstens einmal am Tag bei theme.michael-gahn.de (Plugin, Version, Shopsystem, Domain, zufällige ID). Abschaltbar in den Einstellungen.';
assert(str_contains((string) simplexml_load_file($plugin . '/info.xml')->Description, $kurz));

// Freigegebener Hinweis, kein Entwurf mehr
assert(!str_contains(file_get_contents($plugin . '/src/Install/Settings.php') ?: '', 'ENTWURF'));
assert(str_contains(Settings::HINT, 'jederzeit abschalten'));

// Ohne Client kein Schalter
assert(UpdateBridge::installReporting(null) === null);

if (!defined('URL_SHOP')) {
    define('URL_SHOP', 'https://shop.example.test');
}
$root = sys_get_temp_dir() . '/mgd-install-test-' . bin2hex(random_bytes(4));
mkdir($root . '/templates_c', 0700, true);

// Ungültige Version = null, nie Exception
$ic = UpdateBridge::installClient($root, '1.5.0');
assert($ic instanceof InstallClient && $ic->product() === UpdateBridge::PRODUCT);
assert(is_file(UpdateBridge::dataDir($root) . '/.htaccess'));

// Ab Werk an, abschalten ohne Eintrag = kein Netz
$rep = UpdateBridge::installReporting($ic);
assert($rep !== null && ($rep->isEnabled)() === true);
assert(($rep->status)() === 'Noch nicht gemeldet.');
($rep->setEnabled)(false);
assert($ic->isEnabled() === false && $ic->ping(true) === false);
assert(($rep->status)() === 'Meldung ist ausgeschaltet.');
($rep->setEnabled)(true);
assert($ic->isEnabled() === true);

// Update-Seite: Schalter da, Speichern nur mit CSRF + Recht
$http = new class implements HttpClient {
    public int $calls = 0;
    public function request(string $method, string $url, array $headers = [], ?string $body = null, int $maxBytes = 1048576): HttpResponse
    {
        $this->calls++;
        return new HttpResponse(503);
    }
    public function download(string $url, array $headers, string $targetFile, int $maxBytes): HttpResponse
    {
        $this->calls++;
        return new HttpResponse(503);
    }
};
$pings = 0;
$enabled = true;
$fake = new Plugin\MGD_EU_Garantiehinweise\Update\Admin\InstallReporting(
    static function () use (&$pings): void { $pings++; },
    static function () use (&$enabled): bool { return $enabled; },
    static function (bool $on) use (&$enabled): void { $enabled = $on; },
    static fn (): string => 'Noch nicht gemeldet.',
    Settings::HINT,
);
$store = new JsonFileStateStore($root . '/state.json');
$cfg = new ClientConfig(UpdateBridge::PRODUCT, 'free', '1.5.0', 'shop.example.test', new KeyRing(), $root, '', ['info.xml'], ClientConfig::DEFAULT_BASE_URL, UpdateBridge::SIMPLE_URL);
$client = new UpdateClient($cfg, $http, new NullLicenseProvider(), $store);
$page = static fn (bool $csrf) => new UpdatesPage($client, $store, static fn (): bool => $csrf, static fn (): bool => true, '<input type="hidden" name="jtl_token" value="x">', 'Test', '1.5.0', null, $fake);

$html = $page(true)->handle('GET', [])['html'];
assert(str_contains($html, 'name="install_report"') && str_contains($html, ' checked'));
assert(str_contains($html, htmlspecialchars(Settings::HINT, ENT_QUOTES)));
assert($http->calls === 0 && $pings === 1); // Meldung läuft auch bei ausgeschalteter Update-Prüfung

$page(false)->handle('POST', ['mgd_update_action' => 'save_install']);
assert($enabled === true);
$page(true)->handle('POST', ['mgd_update_action' => 'save_install']);
assert($enabled === false);
$page(true)->handle('POST', ['mgd_update_action' => 'save_install', 'install_report' => '1']);
assert($enabled === true && $http->calls === 0);

// Aufräumen
exec('rm -rf ' . escapeshellarg($root));
