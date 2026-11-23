<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Admin;

use Plugin\MGD_EU_Garantiehinweise\Kit\Platform\Jtl\JtlKit;
use Plugin\MGD_EU_Garantiehinweise\Kit\Platform\Jtl\Setup;

// Anschluss an den MGD Baukasten (Kit). Holt vorher die alten Daten aus 1.5.x ab
final class KitBridge
{
    // alter Datenordner bis 1.5.x - Baukasten kennt nur mgd-update-client/<PluginID>, darum selber holen
    public const OLD_FOLDER = 'mgd-update/eu-garantiehinweise';
    // neuer Ordner vom Baukasten (klein geschriebene Plugin-ID + _update)
    public const NEW_FOLDER = 'mgd_eu_garantiehinweise_update';
    public const FILES = ['install.json', 'state.json'];
    public const MAX_BYTES = 65536;

    /** Kit fürs Plugin, null = geht nicht (dann passiert einfach nichts) */
    public static function kit(object $plugin): ?JtlKit
    {
        try {
            self::adoptOld();

            return Setup::forShop($plugin);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * install.json + state.json einmal rüberkopieren (nur wenn im neuen Ordner noch nix liegt)
     * Alte Dateien danach > *.migrated, sonst käme nach "Daten löschen" die abgemeldete ID wieder zurück
     *
     * @return list<string> übernommene Dateien
     */
    public static function adoptOld(?string $compiledBase = null): array
    {
        $done = [];
        try {
            $compiledBase ??= defined('PFAD_ROOT') && defined('PFAD_COMPILEDIR') ? (string) constant('PFAD_ROOT') . (string) constant('PFAD_COMPILEDIR') : '';
            $compiledBase = rtrim($compiledBase, '/\\');
            $old = $compiledBase . '/' . self::OLD_FOLDER;
            if ($compiledBase === '' || !is_dir($old) || is_link($old)) {
                return [];
            }
            $new = Setup::dataDir($compiledBase, self::NEW_FOLDER);
            if ($new === null || realpath($old) === realpath($new)) {
                return [];
            }
            foreach (self::FILES as $name) {
                $src = $old . '/' . $name;
                $dst = $new . '/' . $name;
                if (!is_file($src) || is_link($src)) {
                    continue;
                }
                if (!is_file($dst) && filesize($src) <= self::MAX_BYTES) {
                    $data = (string) file_get_contents($src);
                    if (is_array(json_decode($data, true)) && file_put_contents($dst, $data, LOCK_EX) !== false) {
                        @chmod($dst, 0600);
                        $done[] = $name;
                    }
                }
                // alt stilllegen, sobald im neuen Ordner was liegt
                if (is_file($dst)) {
                    @rename($src, $src . '.migrated');
                }
            }
        } catch (\Throwable) {
        }

        return $done;
    }
}
