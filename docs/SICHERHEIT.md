# Sicherheit und Datenschutz

## Datenminimierung

Das Plugin speichert keine personenbezogenen Daten, setzt keine Cookies, führt kein Tracking durch und überträgt keine Shopdaten an Dritte. Alle EU-Grafiken liegen lokal im Plugin.

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

## Erweiterung 1.1.0

Native JTL-Settings statt eigener Formulare; eigene Werte werden zusätzlich strikt normalisiert. Keine Upload-/REST-Endpunkte. Herstellerdateien: nur lokale Original-PNG/JPG unter mediafiles/mgd-garan, sichere Dateinamen, realpath-Begrenzung auch für Symlinks, echte Bildtypprüfung, Größenlimit, SHA-256. Hashprüfung erkennt Änderungen, nicht den rechtlichen Inhalt.

Bedingungen-Links: ausschließlich HTTPS mit öffentlichem DNS-Namen, ohne Credentials, Query, Fragment, Steuerzeichen oder ungewöhnliche Ports. Keine serverseitigen Requests und keine DNS-Auflösung. Öffentliche DNS-Namen können dennoch anderswohin auflösen; es findet keine inhaltliche oder Erreichbarkeitsprüfung statt. Links werden erst nach Nutzerklick geöffnet und senden keinen Referrer.

Keine Garantieverträge, personenbezogenen Dokumente oder Zugangstokens in den öffentlichen Labelordner legen. Keine Shopdaten im Repository. Kein eigenes Logging von Artikeln, URLs oder Kunden. Die Dateiablage erfolgt über vorhandene geschützte Serverwerkzeuge und liegt außerhalb des Pluginpakets.
