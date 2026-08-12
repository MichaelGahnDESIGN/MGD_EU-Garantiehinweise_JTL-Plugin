# Offizielle EU-Quellen und Dateiintegrität

Das Plugin liefert die verwendeten Grafiken lokal aus. Dadurch entstehen beim Seitenaufruf keine Verbindungen zu fremden Servern. Die Bilddateien wurden nicht grafisch verändert; ihre SHA-256-Prüfsummen stehen in `assets/eu/manifest.json`.

## Primärquellen

- [EU-Kommission: Leitfaden und hochauflösende Dateien](https://commission.europa.eu/publications/practical-guidelines-and-high-resolution-vector-files-eu-notice-and-label-product-guarantees_en)
- [Delegierte Verordnung (EU) 2025/1960](https://eur-lex.europa.eu/eli/reg_del/2025/1960/oj)
- [Your Europe: Garantien für Verbraucher](https://europa.eu/youreurope/citizens/consumers/shopping/guarantees-returns/index_de.htm)

Abrufdatum der eingebundenen Dateien: **12. August 2026**.

## Technische Auswahl

- Deutscher und englischer Gewährleistungshinweis: offizielle farbige SVG-Dateien.
- GARAN-Auslöser: offizielle Variante `nested display` für die kompakte Darstellung am Produkt.
- GARAN-Detailansicht: offizielle farbige vollständige SVG-Datei.

Das Plugin verändert weder Farben noch Seitenverhältnis oder Inhalt. Wenn eine Prüfsumme abweicht, wird die betroffene Grafik nicht ausgegeben und im Systemstatus als fehlerhaft gemeldet.
