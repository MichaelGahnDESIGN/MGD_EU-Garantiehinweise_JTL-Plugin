# Einstellungen im JTL-Backend

Speicherung und Zugriffs-/CSRF-Schutz erfolgen durch die nativen JTL-Settingslinks. Das Plugin hat keine eigene Schreibroute, keine neue Datenbanktabelle und keinen Upload-Endpunkt. Die 19 Einstellungen werden einzeln in `src/Configuration/Definitions/` dokumentiert; der Test gleicht sie mit `info.xml` ab.

## Gewährleistung

Hauptschalter; Produktseite, Warenkorb und Bestellabschluss jeweils an/aus; maximale Grafikbreite 640/800 Pixel oder volle Breite; Ausrichtung links/zentriert; äußerer Rahmen an/aus; Begleittext an/aus. Die Grafik, ihre Farben, Proportionen und Texte sind nicht editierbar. Ein Abschalten benötigt eine anderweitige geeignete Einbindung. Kein automatischer Stichtagsschalter.

## Sprachauswahl

Automatisch nach Shopsprache (Standard), fest Deutsch oder fest Englisch. Ausweichsprache DE/EN. Der Fallback ist eine technische Wahl, keine Aussage zur zulässigen Sprache in einem anderen Zielmarkt. Deutsche und englische Checkoutwege real prüfen.

## GARAN

Hauptschalter, direkt oder Dialog, Position nach Galerie oder vor Buybox, maximale Breite 480/640/800 Pixel oder volle Breite. Wawi-Attributpräfix frei innerhalb der sicheren Syntax: Kleinbuchstaben/Zahlen/Unterstrich, mit Buchstabe beginnen, mit Unterstrich enden, maximal 40 Zeichen. Standard `mgd_garan_`.

Die Kriterien für die Garantie, Dateiprüfung und pro Artikel erforderliche Angaben sind nicht abschaltbar. Eine globale Aktivierung allein kennzeichnet kein Produkt.

## Template

Produkt-, Warenkorb-, Bestellbutton- und Galerieanker sind einzeln konfigurierbar. Zulässig ist genau eine einfache ID (`#mein-button`) oder Klasse (`.meine-buybox`), keine Selektorlisten, HTML, Attribute oder JavaScript. Es wird der erste Treffer verwendet. IDs sind vorzuziehen. Alle Anker müssen unterscheidbar und eindeutig sein; die Konfiguration garantiert nicht, dass ein Element im tatsächlichen Template existiert.

Fehlt ein notwendiger Anker, gibt es an dieser Stelle keine Anzeige. Eine unbekannte Seite wird nicht verändert. Checkout-Erkennung hat Vorrang vor Warenkorb und Produkt. Anker niemals auf globale Navigationselemente legen.

## Systemstatus und tatsächliche Abnahme

Systemstatus zeigt aktuelle Einstellungen und Prüfsummen der amtlichen DE-/EN-Grafiken sowie des kompakten GARAN-Labels. Er kann keine sichtbare Lage, ausreichende Grafikgröße, Herstellerinhalte oder rechtliche Zulässigkeit feststellen. Die Darstellung muss nach jeder Template-/Cache-/Sprachänderung geprüft werden. Bei kleinen Viewports erscheint ergänzend die Textentsprechung des Hinweises; sie ersetzt nicht die amtliche Grafik.
