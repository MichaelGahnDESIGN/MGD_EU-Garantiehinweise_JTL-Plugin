# Offizielle EU-Quellen und Dateiintegrität

Das Plugin liefert die verwendeten Grafiken lokal aus. Dadurch entstehen beim Seitenaufruf keine Verbindungen zu fremden Servern. Die Bilddateien wurden nicht grafisch verändert; ihre SHA-256-Prüfsummen stehen in `assets/eu/manifest.json`.

## Primärquellen

- [EU-Kommission: Leitfaden und hochauflösende Dateien](https://commission.europa.eu/publications/practical-guidelines-and-high-resolution-vector-files-eu-notice-and-label-product-guarantees_en)
- [Durchführungsverordnung (EU) 2025/1960](https://eur-lex.europa.eu/eli/reg_impl/2025/1960/oj)
- [Your Europe: Garantien für Verbraucher](https://europa.eu/youreurope/citizens/consumers/shopping/guarantees-returns/index_de.htm)

Abrufdatum der eingebundenen Dateien: **12. August 2026**.

## Technische Auswahl

- Deutscher und englischer Gewährleistungshinweis: offizielle farbige SVG-Dateien.
- GARAN-Auslöser: offizielle Variante `nested display` für die kompakte Darstellung am Produkt.
- GARAN-Detailansicht: offizielle farbige vollständige SVG-Datei.

Das Plugin verändert weder Farben noch Seitenverhältnis oder Inhalt. Wenn eine Prüfsumme abweicht, wird die betroffene Grafik nicht ausgegeben und im Systemstatus als fehlerhaft gemeldet.

## Erneuter Abgleich am 09.09.2026

- [IT-Recht Kanzlei: direkte Darstellung](https://www.it-recht-kanzlei.de/gewaehrleistungslabel-korrekte-einbindung.html): Grundlage für die strengere Projektvorgabe, allgemeine Grafik ohne vorgeschaltete Interaktion.
- [Your Europe: Gewährleistungshinweis und GARAN](https://europa.eu/youreurope/business/selling-in-eu/consumer-contracts-guarantees/eu-legal-guarantee-notice-and-garan-label/index_en.htm).
- [JTL-Settingslinks](https://jtl-shop-mkdocs.readthedocs.io/de/latest/shop_plugins/infoxml.html) und [JTL-Pluginvariablen](https://jtl-shop-mkdocs.readthedocs.io/de/latest/shop_plugins/variablen.html) als technische Grundlage.

Die bereits enthaltenen amtlichen Dateien bleiben bytegleich. Für vollständige GARAN-Produktlabel wird die unveränderte, fertig ausgefüllte Hersteller-PNG/JPG aus lokaler Ablage verwendet; die amtliche Leer-Vorlage wird nicht mehr als fertiges Produktlabel ausgegeben. Textentsprechungen DE/EN wurden visuell mit den mitgelieferten amtlichen Hinweisgrafiken abgeglichen.

Der EUR-Lex-Volltextabruf war durch eine Bot-/JavaScript-Prüfung eingeschränkt. Vor Veröffentlichung ist der direkte Verordnungs-/Anhangsabgleich nachzuholen. Weiterverbreitungsrechte amtlicher Grafiken und Herstellerdateien gesondert beachten; keine pauschale Umlizenzierung dieser Inhalte durch die Programmlizenz.
