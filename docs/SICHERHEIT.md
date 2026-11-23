# Sicherheit und Datenschutz

## Datenminimierung

Das Plugin speichert keine personenbezogenen Daten, setzt keine Cookies, führt kein Tracking durch und überträgt keine Shopdaten an Dritte. Ab Version 1.5.0 meldet es sich höchstens einmal am Tag bei theme.michael-gahn.de (Plugin, Version, Shopsystem, Domain, zufällige ID). Das läuft nicht für Shop-Besucher und ist im Reiter „Übersicht“ abschaltbar. Ab Version 1.6.0 holt das Plugin beim Öffnen der Reiter „Übersicht“ und „Weitere Plugins“ eine öffentliche Plugin-Liste und kleine Bilder von theme.michael-gahn.de (nur Abruf, die Gegenseite sieht die Server-IP). Es wird nichts installiert. Alle EU-Grafiken liegen lokal im Plugin.

## Sichere Artikeldaten

- Sämtliche Wawi-Werte werden vor der HTML-Ausgabe escaped.
- Garantiebedingungen müssen über eine absolute HTTPS-Adresse erreichbar sein.
- URLs mit eingebetteten Benutzernamen oder Passwörtern werden abgelehnt.
- Externe Links erhalten `noopener noreferrer`.
- Bei unvollständigen Daten wird das GARAN-Label nicht ausgegeben.

## Dateiintegrität

Die SHA-256-Werte der offiziellen EU-Dateien stehen in `assets/eu/manifest.json`. Vor der Ausgabe wird die jeweilige Datei geprüft. Eine veränderte oder fehlende Grafik wird nicht ausgeliefert.

## Betrieb

Nach Plugin- oder Shop-Updates sind Produktseite, Warenkorb und Bestellabschluss zu prüfen. Änderungen am aktiven Template können andere DOM-Anker verwenden; die Kernfunktion ist für NOVA und darauf basierende ChildThemes ausgelegt.
