# JTL: konfigurierbare EU-Kennzeichnungen

Michael hat am 09.09.2026 die Erweiterung des bestehenden GitHub-Projekts für beide Kennzeichnungen beauftragt. Reihenfolge: JTL, Shopware, WooCommerce. Dieser Auftrag erlaubt Entwicklung und einen überprüfbaren GitHub-Änderungsvorschlag; Release und Live-Installation bleiben getrennt freizugeben.

## Entscheidung

Native JTL-Settings statt eigener Speicherformulare: weniger eigener sicherheitskritischer Code, konsistentes Backend, keine zusätzlichen Tabellen. Ein reines CSS-Update würde die gewünschte Konfiguration nicht erfüllen; eine parallele Artikelverwaltung würde Wawi-Synchronisierung und Zuständigkeiten unnötig vervielfachen. Standard ist daher Wawi als Artikelquelle und JTL-Plugin als globale Konfiguration. Eine optionale Rückfrage zur zusätzlichen Artikelverwaltung läuft.

## Verhalten

- Allgemeiner Hinweis: direkt sichtbare unveränderte DE-/EN-Grafik. Schalter für Produkt/Warenkorb/Checkout, Sprache/Fallback, große Breiten, Rahmen, Begleittext und Ausrichtung. Kein Popup für den allgemeinen Hinweis.
- GARAN: pro Artikel bestätigte kostenlose Hersteller-Haltbarkeitsgarantie für das ganze Produkt, Herstellerinformation vorhanden, Dauer in ganzen/halben Jahren über zwei. Zusätzlich lokal hinterlegte ausgefüllte Hersteller-PNG/JPG mit SHA-256; keine Generierung oder Änderung amtlicher Grafiken. Fehlende neue Angaben unterdrücken alte GARAN-Ausgaben beim Upgrade bewusst.
- GARAN direkt oder als zugängliche Detailansicht; ohne JavaScript bleibt ein Link zur vollständigen lokalen Datei. Einfügepunkt Galerie oder Buybox, Größe und Attributpräfix konfigurierbar.
- Template-Anker ausschließlich einfache IDs/Klassen. Keine beliebigen Selektoren, HTML-, CSS- oder JavaScript-Eingaben. Kein automatischer Remoteabruf und keine Dateiupload-Route.
- Originale Herstellerdateien liegen außerhalb des Pluginordners unter mediafiles/mgd-garan. MIME, Pfad, Größe und Hash werden geprüft. Der Händler kontrolliert den tatsächlichen Labelinhalt.
- Einstellungen in einzelnen Definitionsdateien, XML-Abgleich im Test. Bootstrap verbindet nur Dienste. Konfiguration, Platzierung, Dateiprüfung, Views und Tests getrennt.

## Abnahme

Negative Fach-, URL-, Dateipfad-, Hash- und Konfigurationstests; Rendering/Platzierung separat; Bootstrap mit JTL-Testdoubles; lokale Browserprobe mit synthetischen Artikeln. Tests ersetzen keine echte JTL-Installation. Vor Release erforderlich: Neuinstallation/Update im tatsächlichen JTL-Shop, Sprachwechsel, Wawi-Variantenwechsel, Checkout-Aktualisierung, Lesbarkeit auf echten Geräten und konkrete Händlerfreigabe.
