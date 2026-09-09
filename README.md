# MGD EU-Garantiehinweise für JTL-Shop 5

**Entwicklungsstand 1.1.0 – noch kein freigegebenes Release und keine belegte JTL-Shopabnahme.**

Das Plugin unterstützt den allgemeinen EU-Gewährleistungshinweis und das produktbezogene GARAN-Label. Deutsch und Englisch werden mit unveränderten lokalen EU-Grafiken unterstützt. Es verwendet keine eigenen Cookies, kein Tracking und keine externen Laufzeitressourcen.

> Keine Rechtsberatung: Einstellungen und technische Tests bestätigen keine Rechtskonformität einer Händlerkonfiguration. Anwendungsbereich, Herstellerdaten, Darstellung und Kaufwege müssen vor Live-Einsatz geprüft werden.

## Neue Konfiguration

19 native JTL-Einstellungen, aufgeteilt in Gewährleistung, Sprachauswahl, GARAN und Template:

- Beide Labeltypen getrennt aktivieren; allgemeiner Hinweis auf Produktseite, Warenkorb und vor Bestellabschluss einzeln wählbar.
- Hinweis immer vollständig direkt sichtbar. Große Maximalbreiten, Ausrichtung, äußerer Rahmen und Begleittext wählbar; keine Popup-Option für den allgemeinen Hinweis.
- Automatische Shopsprache oder feste Sprache, konfigurierbare Ausweichsprache.
- GARAN vollständig direkt oder kompakt mit zugänglichem Detaildialog; lokaler Dateilink als Fallback ohne JavaScript.
- GARAN-Position, Größe, Wawi-Attributpräfix und einfache Template-Anker einstellbar.

## GARAN sicher pflegen

Die fünf bisherigen Wawi-Attribute reichen ab 1.1.0 nicht mehr. Zusätzliche Einzelbestätigungen und eine vollständig ausgefüllte lokale Hersteller-PNG/JPG mit SHA-256 sind erforderlich. Fehlende Daten oder defekte Dateien unterdrücken das GARAN-Label. Der allgemeine Hinweis bleibt davon unabhängig.

Details: [Wawi-Attribute](docs/JTL-WAWI-ATTRIBUTE.md), [Einstellungen](docs/EINSTELLUNGEN.md), [Update/Installation](docs/INSTALLATION.md), [Architektur](docs/ARCHITEKTUR.md), [Sicherheit](docs/SICHERHEIT.md), [Teststand](docs/TESTMATRIX.md), [rechtliche Abgrenzung](docs/RECHTLICHE-ABGRENZUNG.md).

**Mobile Grenze:** Eine amtliche Grafik kann auf schmalen Geräten trotz voller Breite sehr kleine Schrift enthalten. Die zusätzliche Textentsprechung hilft beim Lesen, ersetzt aber nicht die notwendige Darstellungsprüfung. Keine pauschale mobile oder rechtliche Abnahme.

## Entwicklung

```bash
php -d zend.assertions=1 -d assert.exception=1 tests/run.php
find plugin -name '*.php' -print0 | xargs -0 -n1 php -l
bash scripts/build-release.sh 1.1.0
git diff --check
```

Der Testläufer baut das ZIP immer frisch und vergleicht die enthaltenen Plugin-Dateien mit dem aktuellen Quellcode. `dist/` enthält lokale Testpakete, keine automatische Veröffentlichungsfreigabe. Ein bestehendes 1.0.0-ZIP bleibt historisch erhalten und wird nicht als Prüfnachweis für 1.1.0 verwendet.

Die Entwicklung erfolgt zuerst für JTL, danach separat für Shopware und WooCommerce. Das vorhandene OPC-Portlet bleibt eine redaktionelle Ergänzung, kein Ersatz der vollständigen amtlichen Grafik.

## Urheber und Lizenz

Copyright (c) 2026 Michael Gahn DESIGN - https://Michael-Gahn.de

Programmcode unter **GPL-3.0-or-later**. Siehe [LICENSE](LICENSE). Amtliche Grafiken bleiben unverändert; Herkunft und Hashes siehe [EU-QUELLEN.md](docs/EU-QUELLEN.md). Herstellerdateien gehören nicht ins Repository.
