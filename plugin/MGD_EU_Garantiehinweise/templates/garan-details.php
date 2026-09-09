<?php declare(strict_types=1);
/** Die vollständige, lokal geprüfte Herstellerdatei ersetzt niemals die amtliche Gestaltung durch HTML. */
?>
<img class="mgd-garan__full" src="<?= $escape($fullAssetUrl) ?>" alt="<?= $escape($title . ': ' . $data['brand'] . ', ' . $data['model'] . ', ' . $years) ?>">
<dl class="mgd-garan__data">
    <div><dt><?= $english ? 'Duration' : 'Dauer' ?></dt><dd><?= $escape($years) ?></dd></div>
    <div><dt><?= $english ? 'Producer / brand' : 'Hersteller / Marke' ?></dt><dd><?= $escape($data['brand']) ?></dd></div>
    <div><dt><?= $english ? 'Model' : 'Modell' ?></dt><dd><?= $escape($data['model']) ?></dd></div>
</dl>
<p><a href="<?= $escape($data['terms_url']) ?>" rel="noopener noreferrer"><?= $english ? 'Read guarantee terms' : 'Garantiebedingungen lesen' ?></a></p>
<p><a href="https://europa.eu/youreurope/commercial-guarantee-durability/index.htm" rel="noopener noreferrer"><?= $english ? 'EU information on GARAN (destination of the QR code)' : 'EU-Informationen zu GARAN (Ziel des QR-Codes)' ?></a></p>
