# Architektur

## Ziel

Das Plugin bleibt unabhängig von einem bestimmten ChildTheme. Es nutzt den serverseitigen JTL-Output-Filter und stabile IDs des NOVA-Templates. So bleibt der gesetzliche Hinweis auch ohne JavaScript im HTML vorhanden.

## Bausteine

- `Bootstrap.php`: verbindet JTL-Ereignisse mit den Fachdiensten.
- `src/Frontend`: erzeugt sicheres HTML und platziert es idempotent.
- `src/Garan`: liest und validiert die Wawi-Funktionsattribute.
- `src/Assets`: vergleicht die EU-Dateien mit dokumentierten SHA-256-Prüfsummen.
- `src/Language`: wählt Deutsch oder Englisch mit kontrolliertem Fallback.
- `Portlets`: frei bearbeitbare redaktionelle OPC-Ergänzung.
- `adminmenu`: verständliche Einrichtung, Prüfkriterien und Systeminformationen.

## Platzierungsreihenfolge

1. `#complete-order-button`: unmittelbar vor dem finalen Bestellbutton.
2. `#cart-checkout-btn`: unmittelbar vor dem Wechsel in den Checkout.
3. `#add-to-cart`: unterhalb der Buybox auf der Produktseite.
4. GARAN bevorzugt nach `#image_wrapper`, bei fehlender Galerie vor der Buybox.

Die Plugin-Klassen verhindern doppelte Ausgaben, wenn JTL den Filter mehrfach aufruft.
