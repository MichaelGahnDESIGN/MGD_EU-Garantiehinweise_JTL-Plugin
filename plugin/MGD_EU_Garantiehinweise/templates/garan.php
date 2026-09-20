<?php declare(strict_types=1);
/** Nur GARAN darf kompakt mit Detaildialog erscheinen; der Link funktioniert auch ohne JavaScript. */
$title = $english ? 'Commercial durability guarantee' : 'Hersteller-Haltbarkeitsgarantie';
$dialog = $settings->get('garan_display') === 'dialog';
?>
<section class="mgd-garan mgd-width-<?= $escape($settings->get('garan_width')) ?>" aria-labelledby="mgd-garan-title">
    <h2 id="mgd-garan-title"><?= $escape($title) ?></h2>
    <?php if ($dialog): ?>
        <a class="mgd-garan__trigger" href="<?= $escape($fullAssetUrl) ?>" data-mgd-dialog-open="mgd-garan-dialog" aria-controls="mgd-garan-dialog" aria-haspopup="dialog" aria-expanded="false">
            <img src="<?= $escape($compactAssetUrl) ?>" alt="">
            <span><?= $escape($data['brand']) ?> · <?= $escape($data['model']) ?> · <?= $escape($years) ?></span>
        </a>
        <dialog class="mgd-garan__dialog" id="mgd-garan-dialog" aria-labelledby="mgd-garan-dialog-title">
            <form method="dialog"><button class="mgd-garan__close" autofocus aria-label="<?= $english ? 'Close' : 'Schließen' ?>">×</button></form>
            <h2 id="mgd-garan-dialog-title"><?= $escape($title) ?></h2>
            <?php require __DIR__ . '/garan-details.php'; ?>
        </dialog>
    <?php else: ?>
        <?php require __DIR__ . '/garan-details.php'; ?>
    <?php endif; ?>
</section>
