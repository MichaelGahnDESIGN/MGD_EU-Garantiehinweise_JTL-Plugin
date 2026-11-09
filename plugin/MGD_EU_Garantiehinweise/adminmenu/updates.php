<?php declare(strict_types=1);

use JTL\Helpers\Form;
use JTL\Shop;
use Plugin\MGD_EU_Garantiehinweise\Admin\UpdateBridge;

require_once dirname(__DIR__) . '/src/Admin/UpdateBridge.php';

// Updates Menue
$pluginDir = dirname(__DIR__);
$dataDir = UpdateBridge::dataDir(defined('PFAD_ROOT') ? (string) PFAD_ROOT : $pluginDir);
$version = isset($oPlugin) && is_object($oPlugin) ? (string) $oPlugin->getMeta()->getVersion() : '';
$shopVersion = defined('APPLICATION_VERSION') ? (string) APPLICATION_VERSION : '';
$domain = UpdateBridge::domainFromUrl((string) Shop::getURL());

// Rechte Check, ohne Account = kein Zugriff
$canManage = static function (): bool {
    try {
        return Shop::Container()->getAdminAccount()->permission('PLUGIN_ADMIN_VIEW');
    } catch (\Throwable) {
        return false;
    }
};

$result = ['html' => '', 'deliver' => null];
try {
    if (!UpdateBridge::prepareDataDir($dataDir)) {
        throw new \RuntimeException('Datenordner nicht anlegbar.');
    }
    $page = UpdateBridge::page(
        $pluginDir,
        $dataDir,
        $version,
        $domain,
        $shopVersion,
        static fn (): bool => Form::validateToken(), // CSRF Check
        $canManage,
        Form::getTokenInput()
    );
    $result = $page->handle((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'), $_POST);
} catch (\Throwable) {
    $result['html'] = '<p class="alert alert-danger">Der Update-Bereich ist gerade nicht verfügbar. Das Plugin arbeitet normal weiter.</p>';
}

// ZIP Download (nur geprüfte Datei aus Quarantäne)
if (is_string($result['deliver'])) {
    $zip = UpdateBridge::safeZipPath($result['deliver'], $dataDir);
    if ($zip !== null && !headers_sent()) {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($zip) . '"');
        header('Content-Length: ' . (string) filesize($zip));
        header('Cache-Control: private, no-store');
        header('X-Content-Type-Options: nosniff');
        readfile($zip);
        exit;
    }
    $result['html'] .= '<p class="alert alert-warning">Download gerade nicht möglich. Bitte Paket erneut prüfen lassen.</p>';
}
?>
<div class="card"><div class="card-body">
    <?= $result['html'] ?>
</div></div>
