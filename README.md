# MGD EU-Garantiehinweise für JTL-Shop 5

Shop-unabhängiges Open-Source-Plugin für den allgemeinen EU-Gewährleistungshinweis und das artikelabhängige GARAN-Label in JTL-Shop 5.5 bis zur jeweils aktuellen 5.x-Version.

> Rechtlicher Hinweis: Das Plugin setzt technische Darstellungen um und ersetzt keine individuelle Rechtsberatung. Shopbetreiber müssen Inhalte, Sortiment, Garantiebedingungen und den konkreten Anwendungsbereich rechtlich prüfen lassen.

## Funktionen

- Serverseitige Ausgabe des Gewährleistungshinweises auf Artikeldetailseiten, im Warenkorb und vor dem finalen Bestellbutton.
- Offizielle EU-Grafiken werden unverändert und datenschutzfreundlich lokal ausgeliefert.
- GARAN-Label nur bei vollständig gepflegten und gültigen Artikeldaten.
- Deutsch und Englisch mit deutschem Fallback.
- Redaktionelles OPC-Portlet für frei platzierbare Zusatzinformationen.
- Keine Cookies, kein Tracking und keine externen Laufzeitaufrufe.

## Installation

1. Das Release-ZIP im JTL-Shop-Backend unter **Plugins > Plugin-Manager > Upload** hochladen.
2. `MGD_EU_Garantiehinweise` installieren und aktivieren.
3. Shop- und Template-Cache leeren.
4. Produktseite, Warenkorb und letzten Checkout-Schritt in beiden Sprachen prüfen.

## GARAN über JTL-Wawi steuern

Das GARAN-Label wird nur ausgegeben, wenn alle Funktionsattribute gültig sind:

| Funktionsattribut | Erwarteter Wert |
|---|---|
| `mgd_garan_aktiv` | exakt `1` |
| `mgd_garan_jahre` | Zahl größer als `2` |
| `mgd_garan_marke` | nicht leer |
| `mgd_garan_modell` | nicht leer |
| `mgd_garan_bedingungen_url` | absolute HTTPS-Adresse |

Fehlerhafte oder unvollständige Daten führen bewusst dazu, dass kein GARAN-Label erscheint. Zugangsdaten in URLs werden abgewiesen.

## OPC-Portlet

Das Portlet **EU-Garantiehinweise** bietet editierbare Felder für Überschrift, Richtext, Linktext und einen internen Shop-Pfad. Externe, protokoll-relative und ausführbare Linkziele werden nicht ausgegeben. Das Portlet ist eine redaktionelle Ergänzung. Die automatisch eingebauten Pflichtstellen dürfen dadurch nicht ersetzt oder entfernt werden.

## Entwicklung und Prüfung

```bash
php tests/run.php
find plugin -name '*.php' -print0 | xargs -0 -n1 php -l
xmllint --noout plugin/MGD_EU_Garantiehinweise/info.xml
```

Details stehen in [ARCHITEKTUR.md](docs/ARCHITEKTUR.md), [SICHERHEIT.md](docs/SICHERHEIT.md) und [EU-QUELLEN.md](docs/EU-QUELLEN.md).

## English summary

Reusable open-source plugin for JTL-Shop 5.5 through the current 5.x release. It renders the statutory EU guarantee notice on the product page, cart and final checkout step and conditionally displays the GARAN label using five JTL-Wawi functional attributes. Official assets are bundled locally; no tracking or third-party runtime requests are used.

Installation and attribute names are language-neutral. German and English storefront output is included. This software provides technical functionality and does not constitute legal advice.

## Urheber und Lizenz

Copyright (c) 2026 Michael Gahn DESIGN - https://Michael-Gahn.de

Veröffentlicht unter **GPL-3.0-or-later**. Siehe [LICENSE](LICENSE).
