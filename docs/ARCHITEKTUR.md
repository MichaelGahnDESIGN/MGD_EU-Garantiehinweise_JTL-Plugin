# Architektur ab 1.1.0

`Bootstrap.php` registriert Autoloading und den bestehenden JTL-Outputfilter. `Frontend/ShopOutput.php` verbindet Settings, Seitenerkennung, amtliche Assetintegrität, Wawi-Datenprüfung, lokale Herstellerdatei und Renderer. Beide Labeltypen werden unabhängig geprüft: ein deaktivierter oder defekter allgemeiner Hinweis verhindert nicht pauschal GARAN und umgekehrt.

- `Configuration/Definitions/`: je Einstellung eine deutsche Definition; `Settings.php` normalisiert JTL-Werte durch Listen und enge Syntax.
- `Frontend/ContextDetector.php`: erkennt Seiten an konfigurierten einfachen Ankern; `DocumentPlacementService.php`: idempotente Platzierung.
- `Garan/`: reine Fachlogik und Wawi-Adapter, ohne Datenbankschreibzugriff.
- `Security/`: HTTPS-Ziele und lokale Hersteller-PNG/JPG samt Hashprüfung.
- `templates/`: getrennte Ansichten für Hinweis, Textentsprechungen und GARAN.
- `Admin/SystemStatus.php`: technische Daten ohne Kunden-/Artikelinhalte.
- JTL-Settingslinks in `info.xml`: JTL übernimmt die Speicherung in seinen vorhandenen Plugin-Einstellungstabellen. Keine eigenen Tabellen, Geheimnisse oder Schema-Migrationen.

Der allgemeine Hinweis wird direkt ausgegeben, GARAN optional als Dialog. Nur wenn tatsächlich ein GARAN-Dialog gerendert wird, wird das lokale Dialogskript geladen. CSS kommt nur bei mindestens einer Kennzeichnung hinzu.

Herstellerdateien liegen unter `mediafiles/mgd-garan` außerhalb des Pluginverzeichnisses. Originale amtliche Grafiken werden nicht verändert. Das Plugin lädt keine Dateien herunter, erstellt keine Garantieverträge und ergänzt keine Bestelldaten.

Der DOM-Testadapter prüft logische Platzierung und Wiederholungen. Er ist keine echte phpQuery-/JTL-Integration. JTL-5.5-Mindestversion bleibt deklariert; höhere konkrete 5.x-Versionen sind erst nach tatsächlicher Shopabnahme bestätigt.
