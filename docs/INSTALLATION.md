# Installation, Update und Rückfall

## Voraussetzungen

- JTL-Shop ab Version 5.5 bis zur aktuellen 5.x-Version
- PHP ab 8.1
- NOVA oder ein auf NOVA basierendes ChildTheme

## Installation

1. Vor Änderungen Shopdateien und Datenbank sichern.
2. Das Release-ZIP unter **Plugins > Plugin-Manager > Upload** auswählen.
3. `MGD_EU_Garantiehinweise` installieren und aktivieren.
4. Shop- und Template-Cache leeren.
5. Produktseite, Warenkorb und letzten Bestellschritt auf Deutsch und Englisch prüfen.
6. Einen für GARAN vorbereiteten Testartikel sowie einen normalen Artikel prüfen.

## Update

Vor einem Update immer ein Backup erstellen. Das neue Release-ZIP über den Plugin-Manager einspielen und anschließend Cache sowie die drei Pflichtpositionen prüfen. Offizielle Grafikdateien dürfen nicht manuell verändert werden.

Ab 1.0.1 zeigt **Systemstatus** bei aktivierter GitHub-Prüfung eine neuere stabile Version und den geprüften ZIP-Link an. Der Abruf ist auf höchstens einmal pro Stunde und Shop begrenzt. Das ZIP wird anschließend bewusst im JTL-Plugin-Manager hochgeladen; erst dessen Update-Schritt führt den Plugin-Lebenszyklus aus. Die automatische Erkennung ist kein unbeaufsichtigtes Dateiüberschreiben. Vor dem ersten produktiven Einsatz den kompletten Ablauf auf einem Testshop prüfen.

## Rückfall und Deinstallation

Bei einem Problem das Plugin im Plugin-Manager deaktivieren. Dadurch endet die Ausgabe sofort, ohne Artikeldaten in JTL-Wawi zu verändern. Nach einer Deinstallation bleiben die Wawi-Funktionsattribute erhalten.

Das OPC-Portlet ist eine redaktionelle Ergänzung. Es ersetzt weder die automatische Artikeldetail-Ausgabe noch Warenkorb oder Bestellabschluss.
