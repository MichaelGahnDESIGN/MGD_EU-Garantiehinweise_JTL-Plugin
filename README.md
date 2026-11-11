<!-- MGD-HEADER -->
<p align="center"><a href="https://Michael-Gahn.de"><img src="assets/mgd-logo.png" alt="Michael Gahn DESIGN" width="48"></a></p>

<p align="center"><img src="assets/banner.svg" alt="MGD EU Garantiehinweise JTL Plugin" width="100%"></p>

<p align="center">
  <img alt="Lizenz" src="https://img.shields.io/github/license/MichaelGahnDESIGN/MGD_EU-Garantiehinweise_JTL-Plugin?label=Lizenz">
  <a href="https://github.com/MichaelGahnDESIGN/MGD_EU-Garantiehinweise_JTL-Plugin/releases/latest"><img alt="Release" src="https://img.shields.io/github/v/release/MichaelGahnDESIGN/MGD_EU-Garantiehinweise_JTL-Plugin?label=Release"></a>
  <img alt="Sprache" src="https://img.shields.io/badge/Sprache-PHP-2f6fed">
  <a href="https://Michael-Gahn.de"><img alt="by Michael Gahn DESIGN" src="https://img.shields.io/badge/by-Michael%20Gahn%20DESIGN-cd1616"></a>
</p>
<!-- /MGD-HEADER -->

# MGD EU-Garantiehinweise für JTL-Shop 5

Shop-unabhängiges Open-Source-Plugin für den allgemeinen EU-Gewährleistungshinweis und das artikelabhängige GARAN-Label in JTL-Shop 5.5 bis zur jeweils aktuellen 5.x-Version.

> Rechtlicher Hinweis: Das Plugin setzt technische Darstellungen um und ersetzt keine individuelle Rechtsberatung. Shopbetreiber müssen Inhalte, Sortiment, Garantiebedingungen und den konkreten Anwendungsbereich rechtlich prüfen lassen.

## Funktionen

- Serverseitige Ausgabe des Gewährleistungshinweises auf Artikeldetailseiten, im Warenkorb und vor dem finalen Bestellbutton.
- Offizielle EU-Grafiken werden unverändert und datenschutzfreundlich lokal ausgeliefert.
- GARAN-Label nur bei vollständig gepflegten und gültigen Artikeldaten.
- Deutsch und Englisch mit deutschem Fallback.
- Redaktionelles OPC-Portlet für frei platzierbare Zusatzinformationen.
- Keine Cookies oder Tracking. Die Update-Prüfung läuft nur, wenn du sie im Reiter **Updates** einschaltest oder selbst startest.

## Installation

1. Das Release-ZIP im JTL-Shop-Backend unter **Plugins > Plugin-Manager > Upload** hochladen.
2. `MGD_EU_Garantiehinweise` installieren und aktivieren.
3. Shop- und Template-Cache leeren.
4. Produktseite, Warenkorb und letzten Checkout-Schritt in beiden Sprachen prüfen.

## Updates im JTL-Backend

Im Plugin gibt es den Reiter **Updates** (seit Version `1.0.3`). Er ersetzt die frühere GitHub-Abfrage im Reiter **Systemstatus**.

So funktioniert er, Schritt für Schritt:

1. Im JTL-Backend das Plugin öffnen und den Reiter **Updates** wählen.
2. Haken bei **Update-Prüfung einschalten** setzen und speichern. Ohne diesen Haken wird nie etwas abgerufen.
3. Oder auf **Jetzt nach Updates suchen** klicken. Der Klick gilt nur für diese eine Prüfung.
4. Gibt es eine neue Version, erscheint sie mit Kurzinfo. Dann Haken setzen und **Paket herunterladen und prüfen** klicken.
5. Das Plugin prüft Prüfsumme und Inhalt des Pakets. Es wird nichts ausgepackt und nichts installiert.
6. Danach die ZIP speichern und selbst in der JTL-Pluginverwaltung hochladen (nach einem Backup). JTL macht das Update.

Wichtig zu wissen:

- Das Plugin ersetzt nie selbst Dateien. Es gibt keine Ein-Klick-Installation und keine automatische Aktivierung.
- Beim Prüfen sieht der Update-Server die IP-Adresse des Shop-Servers. Es werden keine Besucher-, Kunden- oder Bestelldaten gesendet.
- Zustand und heruntergeladene Pakete liegen in `templates_c/mgd-update/eu-garantiehinweise` im Shop-Verzeichnis, mit einer `.htaccess`, die den Webzugriff sperrt.
- Der Bereich ist nur mit dem Backend-Recht für Plugins nutzbar und alle Aktionen laufen mit JTL-Sicherheits-Token.
- Vor einem Update im echten Shop: erst Backup von Datenbank und Dateien machen. Das alte ZIP aufheben, damit du zurück kannst.

So findet der Bereich Updates:

- Der Update-Kanal arbeitet im einfachen Modus: Eine kleine Textdatei auf theme.michael-gahn.de (`plugin-updates/mgd-eu-garantiehinweise.json`) nennt Version und SHA-256-Prüfsumme, das ZIP kommt von GitHub, die Prüfsumme wird beim Download nachgerechnet. Das ist eine Prüfsumme, keine Signatur.
- Die signierte Variante (mit Schlüsseln in `update/root-keys.json`) ist vorbereitet, aber noch nicht aktiv.

Mehr dazu steht im Pro-Repo in `docs/JTL-UPDATE-CLIENT.md` und `docs/UPDATE-API.md`.

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

<!-- MGD-LEGAL -->
---

## Lizenz

Dieses Projekt steht unter der [GNU GPL v3](https://www.gnu.org/licenses/gpl-3.0.html). Den vollständigen Text enthält die Datei [LICENSE](LICENSE).

## Impressum

**Angaben gemäß § 5 DDG (Digitale-Dienste-Gesetz)**

Michael Gahn DESIGN  
Michael Gahn  
Dr.-Theodor-Brugsch Str. 12  
08529 Plauen  
Sachsen  
Deutschland

Tel.: +49 (0) 151 59156639  
E-Mail: Anfrage@Michael-Gahn.de

Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz:  
Steuernummer: 223/222/02451  
Ust-ID: DE288143343

Wir sind zur Teilnahme an einem Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle weder verpflichtet noch bereit.

**Redaktionell verantwortlich:**

Michael Gahn DESIGN  
Michael Gahn  
Dr.-Theodor-Brugsch Str. 12  
08529 Plauen  
Sachsen  
Deutschland

Tel.: +49 (0) 151 59156639  
E-Mail: Anfrage@Michael-Gahn.de
<!-- /MGD-LEGAL -->
