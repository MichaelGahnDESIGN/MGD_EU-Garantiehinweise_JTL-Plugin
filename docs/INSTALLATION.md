# Installation und Updateprüfung für 1.1.0

Dieser Entwicklungsstand ist nicht für eine ungeprüfte Live-Installation freigegeben. Vor Installation/Release ist Michaels ausdrückliche Freigabe erforderlich.

1. Zielversion von JTL, Template, PHP und verwendete Checkout-/Variantenwege erfassen. Mindestens JTL 5.5 ist deklariert; keine pauschale Abnahme aller 5.x-Versionen.
2. Bestehendes Plugin, Konfiguration, Wawi-Attribute und Herstellerdateien sichern. Ein Update zunächst auf einem isolierten Testshop durchführen.
3. Frisches ZIP über den JTL-Pluginmanager installieren/aktualisieren. Prüfen, dass alle vier neuen Einstellungsreiter mit 19 Feldern erscheinen und gespeichert werden können.
4. Cache leeren; Systemstatus kontrollieren. Anker und Anzeigeorte einstellen.
5. Allgemeine Grafik in DE/EN im Warenkorb/Checkout und nach AJAX-Aktualisierung prüfen. Ausreichende Größe und mobile Lesbarkeit separat abnehmen.
6. GARAN-Attribute gemäß JTL-WAWI-ATTRIBUTE.md ergänzen, vollständige Herstellerdateien außerhalb des Plugins ablegen und Hashes pflegen. Gültigen, ungültigen und variierenden Artikel prüfen.
7. Kaufabschluss, Zahlungsanbieter, Express-/abweichende Kaufwege auf Staging durchgehen. Das Plugin darf den Checkout nicht beeinträchtigen.

## Änderung gegenüber 1.0.0

Die bisherigen fünf Attribute genügen nicht mehr. Neue Pflichtbestätigungen und Herstellerdatei fehlen bei alten Artikeln zunächst; dadurch bleibt GARAN absichtlich verborgen. Keine automatische Datenfreigabe oder Rückkehr zur leeren amtlichen Vorlage. Öffentliche Bedingungen-URLs mit Query/Fragment oder unüblichen Ports werden nicht mehr akzeptiert.

Der allgemeine Hinweis ist nun größer. Die vorhandene Vorlage kann deshalb mehr Platz benötigen. Das ist vor dem Update zu prüfen; kein stilles Miniatur-Fallback.

## Rückfall

Bei Problemen vorheriges geprüftes Pluginpaket wiederherstellen oder das Plugin deaktivieren; Herstellerdateien und Wawi-Daten erhalten. Die frühere 1.0.0-GARAN-Darstellung ist kein rechtlich bestätigter Rückfall: gegebenenfalls GARAN deaktiviert lassen und alternative Kennzeichnung abstimmen. Nach Rollback Cache leeren und Kaufabschluss prüfen. Keine vollständige Datenbankrücksicherung über zwischenzeitliche Bestellungen.

Eine Deinstallation wird von JTL verwaltet. Zuvor Plugin-Einstellungen sichern; Herstellerdateien und Wawi-Attribute werden von diesem Plugin nicht gelöscht.
