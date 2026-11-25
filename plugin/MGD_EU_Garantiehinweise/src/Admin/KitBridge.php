<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Admin;

use Plugin\MGD_EU_Garantiehinweise\Kit\Platform\Jtl\JtlKit;
use Plugin\MGD_EU_Garantiehinweise\Kit\Platform\Jtl\Setup;

// Anschluss an den MGD Baukasten (Kit), alles an einer Stelle
final class KitBridge
{
    // alter Datenordner bis 1.5.x (templates_c/...), Kit holt die Daten selber rüber
    public const LEGACY_FOLDERS = ['mgd-update/eu-garantiehinweise'];

    /** Optionen fürs Kit. $version nur bei updated($old, $new), dann gewinnt die neue Version */
    public static function options(?string $version = null): array
    {
        $options = ['legacyFolders' => self::LEGACY_FOLDERS];
        if ($version !== null) {
            $options['version'] = $version;
        }

        return $options;
    }

    /** Kit fürs Plugin, null = geht nicht (dann passiert einfach nichts) */
    public static function kit(object $plugin, ?string $version = null): ?JtlKit
    {
        return Setup::forShop($plugin, self::options($version));
    }
}
