# Prüfstand 1.1.0 – 09.09.2026

## Lokal bestanden

- PHP 8.5.6: alle 15 Testdateien mit aktivierten Assertions und PHP-Syntaxprüfung.
- Fachlogik: jede einzelne Bestätigung, ganze/halbe Jahre, unzulässige Daten, sichere URLs.
- Lokale Herstellerdatei: echtes Bildformat, fehlende/veränderte Datei, falscher Hash, Pfadflucht und Symlink außerhalb des Labelordners.
- Konfiguration: erlaubte Werte, ungültige CSS-/Präfixeingaben, automatische/feste Sprache, Settings-API-Adapter und Parität der XML-Definitionen einschließlich Auswahlwerte.
- DOM-Testadapter: Produkt/Warenkorb/Checkout, kundenspezifischer Anker, richtige Reihenfolge, Wiederholungen ohne Duplikat, unabhängige Labeltypen, fehlendes Label bei Dateifehlern.
- Systemstatus, amtliche Dateihashes, Portlet, Renderer-Escaping, direkte/kompakte GARAN-Ausgabe und Hinweis ohne Dialog.
- Paket: immer frischer Build, gültige Struktur, jede enthaltene Plugin-Datei mit dem aktuellen Quellcode verglichen.

## Lokale Browserprobe

Reproduzierbar ausschließlich lokal mit synthetischen Daten:

```bash
php -S 127.0.0.1:8767 -t .
```

Dann `/tests/preview.php` und `/tests/preview.php?language=en` öffnen. Die GARAN-Grafik dieser ausdrücklich markierten Probe ist eine amtliche Leer-Vorlage für den Dialogtest, kein freigegebenes Produktlabel. Im Produktionspfad wird stattdessen ausschließlich eine geprüfte Herstellerdatei ausgegeben.

Chromium/Playwright geprüft: DE/EN-Grafiken, Desktop 1100 Pixel und Smartphone 390 Pixel. Bei 390 Pixel kein horizontaler Seitenüberlauf; amtliche Grafik 340 Pixel breit. **Ihre kleine Schrift ist dabei weiterhin ein offener Abnahmepunkt.** Ergänzende Textentsprechung ist auf kleinen Displays sichtbar. Allgemeiner Hinweis vor dem Test-Bestellbutton. GARAN: Enter öffnet, Fokus auf Schließen, Escape schließt und gibt Fokus zurück. Nur lokale Ressourcen, keine Plugin-Cookies, zuletzt keine Konsolenfehler. Der erste Probeaufruf hatte einen fehlenden Favicon-Abruf, keinen Pluginfehler.

## Ausdrücklich noch offen

- Echte Neuinstallation und Update 1.0.0 → 1.1.0 in JTL-Shop einschließlich nativer Einstellungsmaske und Benutzerrechten.
- Echter phpQuery-/Smarty-/Wawi-Datenpfad, AJAX-Checkout und Kindartikelwechsel; der DOM-Testadapter ist dafür kein Ersatz.
- Konkrete JTL-/Templateversionen, fremde Checkout-/Express-Kaufwege, Cachevarianten und echtes Bestellen.
- Lesbarkeit der amtlichen Grafik auf schmalen Geräten und rechtliche Händlerabnahme. Zusätzlicher Text löst die Pflicht zur geeigneten Grafikdarstellung nicht automatisch.
- VoiceOver/NVDA, vollständige WCAG-Prüfung, reale Touchgeräte, Firefox/Safari.
- Erneuter direkter EUR-Lex-Volltext-/Anhangsabgleich und Rechteprüfung vor Veröffentlichung.

CI prüft PHP 8.1–8.5. Ein grüner PHP-Lauf bestätigt keine JTL-Shopkompatibilität. Die Ergebnisse der konkreten GitHub-Ausführung sind am zugehörigen PR abzulesen. Kein Release und keine Live-Installation wurden vorgenommen.
