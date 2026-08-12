# GARAN-Funktionsattribute in JTL-Wawi

Das GARAN-Label erscheint nur, wenn alle fünf Werte gültig am Artikel vorhanden sind. Die strenge Prüfung verhindert eine unbeabsichtigte oder irreführende Kennzeichnung.

| Attribut | Bedeutung | Beispiel |
|---|---|---|
| `mgd_garan_aktiv` | bewusste Freigabe | `1` |
| `mgd_garan_jahre` | Garantiedauer, zwingend größer als zwei Jahre | `3` |
| `mgd_garan_marke` | Marke oder Hersteller | `Beispielmarke` |
| `mgd_garan_modell` | eindeutige Modellbezeichnung | `Modell 100` |
| `mgd_garan_bedingungen_url` | öffentliche HTTPS-Adresse der Bedingungen | `https://example.org/garantie` |

## Gültiges Beispiel

Alle fünf Werte aus der Tabelle werden gepflegt. Das Plugin zeigt die kompakte GARAN-Grafik am Produktbild und die vollständige Grafik mit Dauer, Marke, Modell und sicherem Link im Dialog.

## Bewusst ungültige Beispiele

- `mgd_garan_jahre = 2`: Die freiwillige Haltbarkeitsgarantie überschreitet die gesetzliche Mindestdauer nicht.
- `mgd_garan_bedingungen_url = http://...`: Unverschlüsselte Adressen werden abgelehnt.
- Marke oder Modell fehlt: Das Produkt ist für Verbraucher nicht eindeutig bestimmbar.

Nach einer Wawi-Synchronisierung bitte Shop- und Objektcache leeren und die Ausgabe am Artikel kontrollieren.
