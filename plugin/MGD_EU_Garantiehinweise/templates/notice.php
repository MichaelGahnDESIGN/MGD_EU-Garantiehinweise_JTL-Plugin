<?php declare(strict_types=1);
/** Vollständige amtliche Grafik bleibt stets direkt sichtbar, unabhängig von JavaScript. */
$title = $english ? 'Your statutory guarantee rights' : 'Ihre gesetzlichen Gewährleistungsrechte';
$id = 'mgd-eu-notice-title-' . $context;
$infoUrl = $english ? 'https://europa.eu/youreurope/guarantees' : 'https://europa.eu/youreurope/garantien';
?>
<section class="mgd-eu-notice mgd-width-<?= $escape($settings->get('notice_width')) ?> mgd-align-<?= $escape($settings->get('notice_align')) ?> <?= $settings->get('notice_frame') === 'Y' ? 'mgd-framed' : '' ?>" aria-labelledby="<?= $escape($id) ?>">
    <h2 id="<?= $escape($id) ?>"><?= $escape($title) ?></h2>
    <img class="mgd-eu-notice__image" src="<?= $escape($assetUrl) ?>" alt="<?= $english ? 'Official EU notice about statutory guarantee rights' : 'Offizieller EU-Hinweis zur gesetzlichen Gewährleistung' ?>" aria-hidden="true">
    <div class="mgd-screenreader-text"><?php require __DIR__ . ($english ? '/notice-text-en.php' : '/notice-text-de.php'); ?></div>
    <?php if ($settings->get('notice_copy') === 'Y'): ?>
        <p><?= $english ? 'Statutory rights and a voluntary commercial guarantee are separate. The official notice above explains your statutory rights.' : 'Gesetzliche Gewährleistung und freiwillige Herstellergarantie sind voneinander getrennt. Die amtliche Grafik oben erläutert Ihre gesetzlichen Rechte.' ?></p>
    <?php endif; ?>
    <a href="<?= $escape($infoUrl) ?>" rel="noopener noreferrer"><?= $english ? 'Further information from the EU (destination of the QR code)' : 'Weitere Informationen der EU (Ziel des QR-Codes)' ?></a>
</section>
