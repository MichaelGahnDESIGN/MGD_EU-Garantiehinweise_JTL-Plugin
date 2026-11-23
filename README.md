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
- Keine Cookies und kein Tracking im Shop. Die Update-Prüfung läuft nur, wenn du sie im Reiter **Übersicht** einschaltest oder selbst startest.
- Meldet sich höchstens einmal am Tag bei theme.michael-gahn.de (Plugin, Version, Shopsystem, Domain, zufällige ID). Abschaltbar in den Einstellungen.

## Installation

1. Das Release-ZIP im JTL-Shop-Backend unter **Plugins > Plugin-Manager > Upload** hochladen.
2. `MGD_EU_Garantiehinweise` installieren und aktivieren.
3. Shop- und Template-Cache leeren.
4. Produktseite, Warenkorb und letzten Checkout-Schritt in beiden Sprachen prüfen.

## Die Reiter **Übersicht** und **Weitere Plugins** (ab Version 1.6.0)

Seit Version `1.6.0` hat das Plugin den gemeinsamen MGD-Baukasten. Er ersetzt den alten Reiter **Updates**. Du findest alles im JTL-Backend unter **Plugins**, dann dieses Plugin.

### Reiter **Übersicht**

Hier steht alles auf einer Seite:

- **Info:** Name und Version des Plugins.
- **Updates:** Hier suchst du nach einer neuen Version (siehe unten).
- **Weitere Plugins:** Eine kleine Liste mit anderen Plugins von Michael Gahn DESIGN.
- **Installation melden:** Der Schalter für die Meldung (siehe unten).

### Updates, Schritt für Schritt

1. Im JTL-Backend das Plugin öffnen und den Reiter **Übersicht** wählen.
2. Haken bei **Update-Prüfung einschalten** setzen und speichern. Ohne diesen Haken wird nie nach Updates gesucht.
3. Oder auf **Jetzt nach Updates suchen** klicken. Der Klick gilt nur für diese eine Prüfung.
4. Gibt es eine neue Version, erscheint sie mit Kurzinfo. Dann Haken setzen und **Paket herunterladen und prüfen** klicken.
5. Das Plugin prüft Prüfsumme und Inhalt des Pakets. Es wird nichts ausgepackt und nichts installiert.
6. Danach die ZIP speichern und selbst in der JTL-Pluginverwaltung hochladen (nach einem Backup). JTL macht das Update.

Wichtig zu wissen:

- Das Plugin ersetzt nie selbst Dateien. Es gibt keine Ein-Klick-Installation und keine automatische Aktivierung.
- Beim Prüfen sieht der Update-Server die IP-Adresse des Shop-Servers. Es werden keine Besucher-, Kunden- oder Bestelldaten gesendet.
- Zustand und heruntergeladene Pakete liegen in `templates_c/mgd_eu_garantiehinweise_update` im Shop-Verzeichnis, mit einer `.htaccess`, die den Webzugriff sperrt.
- Nur angemeldete Backend-Benutzer mit dem Recht **Plugins verwalten** sehen etwas. Alle Aktionen laufen mit dem JTL-Sicherheits-Token.
- Vor einem Update im echten Shop: erst Backup von Datenbank und Dateien machen. Das alte ZIP aufheben, damit du zurück kannst.
- Der Update-Kanal arbeitet im einfachen Modus: Eine kleine Textdatei auf theme.michael-gahn.de (`plugin-updates/mgd-eu-garantiehinweise.json`) nennt Version und SHA-256-Prüfsumme, das ZIP kommt von GitHub, die Prüfsumme wird beim Download nachgerechnet. Das ist eine Prüfsumme, keine Signatur.
- Ein Hinweis „Bitte aktualisieren“ erscheint nur, wenn die Update-Prüfung oder **Installation melden** eingeschaltet ist.

### Reiter **Weitere Plugins**

- Zeigt Karten mit anderen MGD-Plugins für JTL-Shop (mit Suche, Kategorien und Details). Dein eigenes Plugin steht nicht dabei.
- **Was passiert beim Öffnen?** Das Plugin holt die öffentliche Plugin-Liste von theme.michael-gahn.de. Der Server sieht dabei die IP-Adresse deines Shop-Servers, aber keine Shop-, Kunden- oder Bestelldaten. Kleine Vorschaubilder werden einmal geholt und im Ordner `mediafiles/mgd-kit-catalog` deines Shops gespeichert. Dein Browser lädt nie Bilder von einem fremden Server.
- Es wird **nichts installiert**. Installiert wird immer nur von Hand in der JTL-Pluginverwaltung.
- Ist theme.michael-gahn.de nicht erreichbar, zeigt das Plugin die letzte gute Liste oder eine eingebaute Ersatzliste. Der Shop läuft normal weiter.

### Update von Version 1.5.x

Du musst nichts tun. Beim ersten Öffnen holt das Plugin die alten Daten (Schalter und Update-Zustand) aus `templates_c/mgd-update/eu-garantiehinweise` in den neuen Ordner. Die alten Dateien bekommen die Endung `.migrated` und werden nicht gelöscht. Deine Einstellungen und deine Installations-ID bleiben so erhalten.

## Installation melden (ab Version 1.5.0)

Das Plugin sagt theme.michael-gahn.de kurz Bescheid, dass es in deinem Shop läuft.

- **Wann?** Beim Installieren, Aktivieren und Updaten. Danach höchstens einmal am Tag, nur im Backend-Bereich und bei Hintergrundläufen des Shops (zum Beispiel Wawi-Abgleich). Besucher im Shop lösen nie etwas aus.
- **Was wird gesendet?** Nur Plugin-Name, Version, Shopsystem, die Domain deines Shops und eine zufällige Installations-ID. Keine Kunden, keine Bestellungen, keine E-Mail.
- **Wozu?** So sieht Michael Gahn DESIGN, wie viele Shops das Plugin nutzen, und kann Updates bereitstellen.
- **Abschalten:** Im Plugin den Reiter **Übersicht** öffnen. Den Haken bei **Installation bei Michael Gahn DESIGN melden** entfernen und **Speichern** klicken. Danach wird nichts mehr gesendet und dein Eintrag auf der Plattform gelöscht.
- **Deinstallieren:** Auch beim Deinstallieren des Plugins wird dein Eintrag auf der Plattform gelöscht. Wählst du dabei „Daten löschen“, verschwindet auch der Datenordner.
- Das Plugin funktioniert immer genauso weiter, auch wenn die Meldung aus ist oder die Plattform nicht erreichbar ist.

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

Reusable open-source plugin for JTL-Shop 5.5 through the current 5.x release. It renders the statutory EU guarantee notice on the product page, cart and final checkout step and conditionally displays the GARAN label using five JTL-Wawi functional attributes. Official assets are bundled locally; no tracking is used. From version 1.5.0 the plugin reports itself to theme.michael-gahn.de at most once a day (plugin, version, shop system, domain, random ID); this can be switched off in the Overview tab. Version 1.6.0 adds the MGD toolkit with the tabs Overview and More plugins (a list of other MGD plugins, nothing is installed automatically).

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
