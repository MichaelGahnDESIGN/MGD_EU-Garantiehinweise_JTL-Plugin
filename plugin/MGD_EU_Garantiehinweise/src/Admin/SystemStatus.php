<?php declare(strict_types=1);
namespace Plugin\MGD_EU_Garantiehinweise\Admin;
use Plugin\MGD_EU_Garantiehinweise\Configuration\Settings;
use Plugin\MGD_EU_Garantiehinweise\Assets\AssetIntegrityService;

/** Liefert ausschließlich technische Statusdaten, niemals Artikel-, Kunden- oder Zugangsdaten. */
final class SystemStatus
{
    /** @return array<string, string> */
    public function inspect(Settings $settings, string $pluginDirectory): array
    {
        $integrity = new AssetIntegrityService($pluginDirectory . '/assets/eu');
        $result = [];
        foreach (['de/legal-guarantee-notice.svg', 'en/legal-guarantee-notice.svg', 'garan/garan-label-nested-display.svg'] as $file) {
            $result['Dateiprüfung: ' . $file] = $integrity->isValid($file) ? 'Prüfsumme stimmt' : 'Fehler: Datei fehlt oder wurde verändert';
        }
        $result['Allgemeiner Hinweis'] = $settings->get('notice_enabled') === 'Y' ? 'Aktiviert' : 'Deaktiviert – alternative Einbindung erforderlich';
        foreach (['product' => 'Produktseite', 'cart' => 'Warenkorb', 'checkout' => 'Bestellabschluss'] as $key => $name) {
            $result[$name] = $settings->noticeEnabled($key) ? 'Aktiviert – Position und Lesbarkeit im Shop prüfen' : 'Keine automatische Hinweisgrafik';
        }
        $result['GARAN'] = $settings->get('garan_enabled') === 'Y'
            ? 'Aktiviert – nur bestätigte Artikel mit geprüfter lokaler Datei' : 'Deaktiviert';
        $result['Sprache'] = $settings->get('language') . '; Ausweichsprache: ' . $settings->get('fallback_language');
        $result['Shop-Abnahme'] = 'Hier nicht automatisch feststellbar. Sprachwechsel, mobile Lesbarkeit, Varianten und Checkout manuell prüfen.';
        return $result;
    }
}
