# Changelog

## Unveröffentlicht

- Neuer Backend-Reiter **Updates** mit dem gemeinsamen MGD Update Client (signierte Update-Prüfung, Paket herunterladen und prüfen).
- Update-Prüfung nur auf Wunsch (Opt-in). Keine Ein-Klick-Installation, kein Datei-Austausch, keine automatische Aktivierung. Installiert wird nur in der JTL-Pluginverwaltung.
- Der Update-Kanal arbeitet im einfachen Modus: Eine kleine Textdatei auf theme.michael-gahn.de (`plugin-updates/mgd-eu-garantiehinweise.json`) nennt Version und SHA-256-Prüfsumme, das ZIP kommt von GitHub, die Prüfsumme wird beim Download nachgerechnet. Das ist eine Prüfsumme, keine Signatur. Solange die Textdatei noch nicht auf dem Server liegt, meldet der Bereich nur einen Fehler beim Abruf. Sonst passiert nichts.
- Neuer Smoke-Test `tests/test-update-client.php` (Rechte, CSRF, kein stiller Netzabruf, Ausgabe escaped).
- Alte GitHub-Abfrage und Einstellung „GitHub-Updatehinweise“ entfernt (ersetzt durch den Update Client).
- Plugin-Version bleibt unverändert bei 1.0.2.

## 1.0.2 - 2026-09-30

- Release-Prüfung verarbeitet einen bereits veröffentlichten Entwurf ohne doppeltes Release.
- ZIP-Inhalte werden unabhängig von ZIP-Zeitstempeln verglichen; jede SHA-256-Datei bestätigt ihr eigenes Paket.
- Plugin-Funktion unverändert. Ein produktiver JTL-Update-Test steht weiterhin aus.

## 1.0.1 - 2026-09-30

- Optionale GitHub-Release-Erkennung im JTL-Backend mit stündlichem Cache.
- Verifizierter Release-Link und ZIP-Download für den JTL-Plugin-Manager.
- Paketversion, Plugin-Identität und Release-Tag werden beim Bau abgeglichen.
- Keine automatische Installation oder Veränderung produktiver Shop-Dateien.

## 1.0.0 - 2026-08-12

- Automatische Hinweise für Produktseite, Warenkorb und Bestellabschluss.
- Artikelabhängiges GARAN-Label über fünf Wawi-Funktionsattribute.
- Offizielle EU-Dateien auf Deutsch und Englisch lokal eingebunden.
- Bearbeitbares OPC-Portlet und vier informative Backend-Reiter ergänzt.
- Integritäts-, Sprach-, URL-, Eligibility- und Renderer-Tests ergänzt.
