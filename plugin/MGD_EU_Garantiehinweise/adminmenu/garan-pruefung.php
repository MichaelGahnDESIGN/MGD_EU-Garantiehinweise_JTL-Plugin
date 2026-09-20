<?php declare(strict_types=1);
/** Pflegehilfe ohne Auslesen oder Offenlegen individueller Artikeldaten. */
if (!defined('PFAD_ROOT')) { return; }
?>
<div class="card"><div class="card-body">
    <h2>GARAN: Voraussetzungen und Herstellerdatei</h2>
    <p>Standardpräfix: <code>mgd_garan_</code>. Die folgenden Suffixe gehören jeweils an diesen oder den eingestellten eigenen Präfix:</p>
    <ul>
        <li><code>aktiv</code>, <code>hersteller</code>, <code>haltbarkeit</code>, <code>kostenlos</code>, <code>ganzes_produkt</code>, <code>information_erhalten</code>, <code>label_geprueft</code>: jeweils exakt <code>1</code>.</li>
        <li><code>jahre</code>: mehr als zwei, ganze oder halbe Jahre (z. B. <code>3</code>, <code>2,5</code>).</li>
        <li><code>marke</code>, <code>modell</code>: passend zur ausgefüllten Herstellerdatei.</li>
        <li><code>bedingungen_url</code>: öffentliche HTTPS-Adresse ohne Zugangsdaten, Queryparameter oder Fragment.</li>
        <li><code>label_datei</code>: PNG-/JPG-Dateiname aus <code>mediafiles/mgd-garan</code> im Shop-Hauptverzeichnis.</li>
        <li><code>label_sha256</code>: SHA-256 der unveränderten Herstellerdatei (64 kleine Hexzeichen).</li>
    </ul>
    <p>Herstellerdateien über den bestehenden geschützten Datei-/Serverzugang ablegen. Kein Upload über dieses Plugin. Nur öffentliche Labeldateien ohne personenbezogene Daten verwenden.</p>
    <p><strong>Update von 1.0.0:</strong> Die bisherigen fünf Attribute reichen nicht mehr aus. Bis alle neuen Angaben vorliegen, erscheint kein GARAN-Label. Nach Änderungen an Modell, Dauer, Garantiebedingungen oder Datei muss die inhaltliche Prüfung erneut erfolgen.</p>
    <p>Die Dateiprüfung beweist nicht, dass Dauer, Hersteller und Modell innerhalb der Grafik richtig ausgefüllt sind. Das muss der Händler anhand der Herstellerinformation kontrollieren. Keine automatische Vererbung oder Rechtsprüfung.</p>
</div></div>
