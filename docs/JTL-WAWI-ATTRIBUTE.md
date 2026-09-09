# GARAN-Funktionsattribute ab 1.1.0

Alle Attribute werden am konkreten Artikel beziehungsweise Kindartikel gepflegt. Standardpräfix `mgd_garan_`; bei eigenem Präfix wird nur dieser Teil ersetzt. Das Plugin übernimmt keine Stammdatenpflege und erzeugt kein Herstellerlabel.

| Attribut | Inhalt |
|---|---|
| `mgd_garan_aktiv` | exakt `1`: Anzeige für diesen Artikel freigegeben |
| `mgd_garan_hersteller` | exakt `1`: Hersteller ist Garantiegeber |
| `mgd_garan_haltbarkeit` | exakt `1`: Haltbarkeitsgarantie, kein bloßes Servicepaket |
| `mgd_garan_kostenlos` | exakt `1`: keine zusätzlichen Kosten |
| `mgd_garan_ganzes_produkt` | exakt `1`: gesamtes Produkt abgedeckt |
| `mgd_garan_information_erhalten` | exakt `1`: passende Herstellerinformationen liegen vor |
| `mgd_garan_label_geprueft` | exakt `1`: Datei, Dauer, Marke, Modell und Bedingungen manuell gegengeprüft |
| `mgd_garan_jahre` | Ganze oder halbe Jahre über zwei, etwa `3`, `2.5`, `2,5`; technisch maximal `99,5` |
| `mgd_garan_marke` | Hersteller/Marke passend zum Label; maximal 400 UTF-8-Bytes |
| `mgd_garan_modell` | Modell passend zum Label; maximal 400 UTF-8-Bytes |
| `mgd_garan_bedingungen_url` | Öffentliche HTTPS-Adresse, keine Zugangsdaten, Queryparameter oder Fragmente |
| `mgd_garan_label_datei` | Etwa `modell-100.png`: Dateiname, kein Pfad und keine URL |
| `mgd_garan_label_sha256` | 64 kleine Hexzeichen der unveränderten Originaldatei |

## Dateiablage

Der Händler erhält ein vollständig ausgefülltes RGB-Label vom Hersteller. Nur PNG/JPG/JPEG, keine beliebigen SVG-Uploads. Im Shop-Hauptverzeichnis über den vorhandenen geschützten Serverzugang den Ordner `mediafiles/mgd-garan` anlegen und das unveränderte Original dort ablegen. Höchstens 10 MiB und 40 Megapixel; Dateiname beginnt mit Buchstabe/Zahl, danach nur Buchstaben/Zahlen/Bindestriche/Unterstriche und die kleingeschriebene Endung. Keine Unterordner im Attribut.

Die Dateiprüfsumme beispielsweise lokal ermitteln:

```bash
shasum -a 256 modell-100.png
```

Nur den Hash als Attribut übernehmen. Die Ablage ist öffentlich: keine individuellen Garantieverträge, Kundendaten oder vertraulichen Unterlagen verwenden. Herstellerdateien liegen außerhalb des Plugins und werden vom Paketupdate nicht ersetzt.

## Verantwortung und Änderungen

Dateihash und Bestätigung prüfen nicht automatisch den Text innerhalb der Grafik. Der Händler muss die Übereinstimmung prüfen. Nach Änderung von Artikelmodell, Dauer, Hersteller, Garantiebedingungen oder Datei `label_geprueft` zunächst auf `0` setzen und erst nach erneuter Prüfung auf `1`. Die Wawi kann diesen redaktionellen Ablauf nicht automatisch durch das Plugin erzwingen. Geänderte Dateibytes bei gleichem Hash unterdrücken die Anzeige automatisch.

Kein Label bei unvollständigen Angaben, Dauer `2` oder `2,1`, veralteten fünf Attributen, ungültiger URL, fehlender Datei oder falschem Hash. Eine leere amtliche Vorlage mit XX/Brand/Model ist kein fertiges Herstellerlabel. Die Template-Datei im amtlichen Assetbestand wird nicht mehr als vollständiges Produktlabel ausgegeben.

## Varianten

Attribute an jedem passenden Kindartikel kontrollieren. Das Plugin liest das von JTL für die aktuelle Seite gelieferte Artikelobjekt; es führt keine eigene Vererbung aus. Ob Wawi/JTL bereits geerbte Attribute liefert, ist in der konkreten Installation zu prüfen. AJAX-Variantenwechsel und abweichende Templates benötigen eine reale Shopabnahme, bevor unterschiedliche Garantiebedingungen freigegeben werden.
