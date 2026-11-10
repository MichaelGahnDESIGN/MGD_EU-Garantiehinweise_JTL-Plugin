<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Admin;

use Plugin\MGD_EU_Garantiehinweise\Update\Admin\UpdatesPage;
use Plugin\MGD_EU_Garantiehinweise\Update\Factory;

// Autoloader für Admin-Seiten (Bootstrap läuft dort nicht immer)
spl_autoload_register(static function (string $class): void {
    $prefix = 'Plugin\\MGD_EU_Garantiehinweise\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $file = dirname(__DIR__) . '/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

// Brücke zwischen JTL-Backend und MGD Update Client. Kein JTL-Code hier drin, damit testbar
final class UpdateBridge
{
    // Produktname Update-API (Free)
    public const PRODUCT = 'mgd-eu-garantiehinweise';
    public const EDITION = 'free';
    public const PLUGIN_NAME = 'EU-Garantiehinweise';
    // Prüfsummen-Datei auf All-Inkl  (einfacher Modus ohne Schlüssel)
    public const SIMPLE_URL = 'https://theme.michael-gahn.de/plugin-updates/mgd-eu-garantiehinweise.json';

    // Datenordner ausserhalb Web: Shop-Verzeichnis templates_c, nicht im Plugin-Ordner (der wird bei Updates ersetzt)
    public static function dataDir(string $shopRoot): string
    {
        return rtrim($shopRoot, '/\\') . '/templates_c/mgd-update/eu-garantiehinweise';
    }

    // Ordner anlegen + .htaccess Deny, falls der Webserver ihn doch ausliefern würde
    public static function prepareDataDir(string $dir): bool
    {
        if (!is_dir($dir) && !@mkdir($dir, 0700, true) && !is_dir($dir)) {
            return false;
        }
        $htaccess = $dir . '/.htaccess';
        if (!is_file($htaccess)) {
            @file_put_contents($htaccess, "# MGD Update Client, kein Webzugriff\nRequire all denied\n<IfModule !mod_authz_core.c>\nDeny from all\n</IfModule>\n");
        }
        if (!is_file($dir . '/index.html')) {
            @file_put_contents($dir . '/index.html', '');
        }

        return true;
    }

    // Domain aus Shop-URL (ohne Port, kleingeschrieben)
    public static function domainFromUrl(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);

        return is_string($host) ? strtolower($host) : '';
    }

    /**
     * @param callable():bool $csrfValid JTL Form::validateToken
     * @param callable():bool $canManage Rechte im Admin
     */
    public static function page(
        string $pluginDir,
        string $dataDir,
        string $installedVersion,
        string $domain,
        string $shopVersion,
        callable $csrfValid,
        callable $canManage,
        string $csrfField,
    ): UpdatesPage {
        return Factory::page(
            rtrim($pluginDir, '/\\') . '/update/root-keys.json',
            $dataDir,
            self::PRODUCT,
            self::EDITION,
            self::PLUGIN_NAME,
            $installedVersion,
            $domain,
            $shopVersion,
            $csrfValid,
            $canManage,
            $csrfField,
            null, // Free: keine Lizenz
            ['info.xml'],
            self::SIMPLE_URL
        );
    }

    // Geprüfte ZIP ausliefern. Nur Dateien direkt unter dem Datenordner/quarantine, nur .zip
    public static function safeZipPath(string $file, string $dataDir): ?string
    {
        $real = realpath($file);
        $base = realpath($dataDir . '/quarantine');
        if ($real === false || $base === false || !is_file($real)) {
            return null;
        }
        if (dirname($real) !== $base || strtolower(pathinfo($real, PATHINFO_EXTENSION)) !== 'zip') {
            return null;
        }

        return $real;
    }

    public static function escape(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
