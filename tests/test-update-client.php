<?php declare(strict_types=1);

/**
 * Smoke-Test für den Update-Bereich. Läuft ohne JTL und ohne Netz (Fake-HTTP, Fake-Callbacks).
 * Der Test-Schlüssel entsteht nur im Speicher und wird nie gespeichert.
 */
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../plugin/MGD_EU_Garantiehinweise/src/Admin/UpdateBridge.php';

use Plugin\MGD_EU_Garantiehinweise\Admin\UpdateBridge;
use Plugin\MGD_EU_Garantiehinweise\Update\Admin\UpdatesPage;
use Plugin\MGD_EU_Garantiehinweise\Update\ClientConfig;
use Plugin\MGD_EU_Garantiehinweise\Update\Crypto\KeyRing;
use Plugin\MGD_EU_Garantiehinweise\Update\Http\{HttpClient, HttpResponse};
use Plugin\MGD_EU_Garantiehinweise\Update\License\NullLicenseProvider;
use Plugin\MGD_EU_Garantiehinweise\Update\Store\JsonFileStateStore;
use Plugin\MGD_EU_Garantiehinweise\Update\UpdateClient;

$plugin = dirname(__DIR__) . '/plugin/MGD_EU_Garantiehinweise';

// Fake-HTTP zählt jeden Netzabruf, liefert immer 503
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

$tmp = sys_get_temp_dir() . '/mgd-update-test-' . bin2hex(random_bytes(4));
mkdir($tmp, 0700, true);
$store = new JsonFileStateStore($tmp . '/state.json');
$roots = (new KeyRing())->add('test-root-1', 'root', sodium_crypto_sign_publickey(sodium_crypto_sign_keypair()));
$cfg = new ClientConfig(UpdateBridge::PRODUCT, 'free', '1.0.2', 'shop.example.test', $roots, $tmp);
$client = new UpdateClient($cfg, $http, new NullLicenseProvider(), $store);

$make = static fn (bool $csrf, bool $right, string $name = 'Test') => new UpdatesPage(
    $client, $store, static fn (): bool => $csrf, static fn (): bool => $right, '<input type="hidden" name="jtl_token" value="x">', $name, '1.0.2'
);

// Ohne Recht: kein Zugriff, kein Netz, auch nicht per POST
$r = $make(true, false)->handle('POST', ['mgd_update_action' => 'check_now'])['html'];
assert(str_contains($r, 'Berechtigung') && !str_contains($r, '<form'));
assert($http->calls === 0);

// Ohne CSRF: keine Aktion
$r = $make(false, true)->handle('POST', ['mgd_update_action' => 'save_settings', 'checks_enabled' => '1'])['html'];
assert(str_contains($r, 'Sicherheits-Token') && !str_contains($r, '<form'));
$r = $make(false, true)->handle('POST', ['mgd_update_action' => 'check_now'])['html'];
assert($http->calls === 0 && $store->load() === []);

// GET ohne Einschalten: kein Netzabruf, Formular da
$r = $make(true, true)->handle('GET', []);
assert($http->calls === 0 && str_contains($r['html'], 'checks_enabled') && $r['deliver'] === null);

// Download ohne Bestätigung: kein Netz
$make(true, true)->handle('POST', ['mgd_update_action' => 'download']);
assert($http->calls === 0);

// Einschalten speichert nur, ruft nichts ab
$make(true, true)->handle('POST', ['mgd_update_action' => 'save_settings', 'checks_enabled' => '1']);
assert(($store->load()['enabled'] ?? false) === true && $http->calls === 0);

// Erst jetzt (eingeschaltet) darf ein GET prüfen
$make(true, true)->handle('GET', []);
assert($http->calls >= 1);

// Klick "Jetzt suchen" mit CSRF ruft ab
$before = $http->calls;
$make(true, true)->handle('POST', ['mgd_update_action' => 'check_now']);
assert($http->calls > $before);

// Anzeige escaped: Plugin-Name und gespeicherte Meldung
$store->save(['last_result' => ['status' => 'update_available', 'message' => '<img src=x onerror=alert(1)>',
    'version' => '9.9.9"><script>', 'changelog' => '<b>x</b>', 'security' => false], 'last_check' => time()] + $store->load());
$html = $make(true, true, '<script>alert(1)</script>')->handle('GET', [])['html'];
assert(!str_contains($html, '<script>') && !str_contains($html, '<img src=x'));
assert(str_contains($html, '&lt;script&gt;') && str_contains($html, '&lt;img src=x'));

// Shipping-Stand: root-keys.json leer = Kanal nicht eingerichtet, kein Netz
$keys = json_decode((string) file_get_contents($plugin . '/update/root-keys.json'), true);
assert(is_array($keys) && ($keys['keys'] ?? null) === []);
$real = UpdateBridge::page($plugin, $tmp . '/real', '1.0.2', 'shop.example.test', '5.5.0', static fn (): bool => true, static fn (): bool => true, '');
$out = $real->handle('POST', ['mgd_update_action' => 'check_now'])['html'];
assert(str_contains($out, 'noch nicht eingerichtet'));

// Brücke: Produktname, Domain, Datenordner, ZIP-Pfade
assert(UpdateBridge::PRODUCT === 'mgd-eu-garantiehinweise' && UpdateBridge::EDITION === 'free');
assert(UpdateBridge::domainFromUrl('https://Shop.Example.de:8443/x') === 'shop.example.de');
assert(UpdateBridge::dataDir('/var/www/shop/') === '/var/www/shop/templates_c/mgd-update/eu-garantiehinweise');
assert(UpdateBridge::prepareDataDir($tmp . '/data') && is_file($tmp . '/data/.htaccess'));
mkdir($tmp . '/data/quarantine', 0700, true);
file_put_contents($tmp . '/data/quarantine/a.zip', 'x');
file_put_contents($tmp . '/data/b.zip', 'x');
assert(UpdateBridge::safeZipPath($tmp . '/data/quarantine/a.zip', $tmp . '/data') !== null);
assert(UpdateBridge::safeZipPath($tmp . '/data/b.zip', $tmp . '/data') === null);
assert(UpdateBridge::safeZipPath($tmp . '/data/quarantine/../b.zip', $tmp . '/data') === null);

// info.xml: Menüpunkt Updates + Datei vorhanden
$xml = simplexml_load_file($plugin . '/info.xml');
assert($xml !== false);
$found = false;
foreach ($xml->Install->Adminmenu->Customlink as $link) {
    if ((string) $link->Filename === 'updates.php') {
        $found = true;
        assert(is_file($plugin . '/adminmenu/updates.php'));
    }
}
assert($found);

// Aufräumen
exec('rm -rf ' . escapeshellarg($tmp));
