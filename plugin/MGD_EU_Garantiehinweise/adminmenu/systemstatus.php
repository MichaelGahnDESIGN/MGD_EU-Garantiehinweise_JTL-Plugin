<?php declare(strict_types=1);

use Plugin\MGD_EU_Garantiehinweise\Update\ReleaseChecker;

require_once dirname(__DIR__) . '/src/Update/ReleaseChecker.php';
$version = '1.0.1';
$enabled = isset($oPlugin) && is_object($oPlugin)
    && $oPlugin->getConfig()->getValue('update_notices') === 'Y';
$update = (new ReleaseChecker())->check($version, $enabled);
$safe = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<div class="card"><div class="card-body">
    <h2>Systemstatus</h2>
    <dl>
        <dt>Plugin-Version</dt><dd><?= $safe($version) ?></dd>
        <dt>Unterstützte JTL-Shop-Versionen</dt><dd>5.5 bis aktuelle 5.x-Version</dd>
        <dt>Frontend-Technik</dt><dd>Serverseitiger JTL-Output-Filter mit stabilen NOVA-Ankern</dd>
        <dt>Datenschutz</dt><dd>Keine Cookies oder Tracking; optionale GitHub-Prüfung nur im Backend</dd>
    </dl>
    <h3>Updates</h3>
    <?php if (!$enabled): ?>
        <p>Die GitHub-Updateprüfung ist deaktiviert.</p>
    <?php elseif (!$update['ok']): ?>
        <p>GitHub konnte gerade nicht geprüft werden. Das Plugin bleibt unverändert.</p>
    <?php elseif ($update['update']): ?>
        <p>Version <?= $safe((string) $update['latest']) ?> ist verfügbar.</p>
        <p><a class="btn btn-primary" href="<?= $safe((string) $update['download_url']) ?>" target="_blank" rel="noopener noreferrer">Geprüftes Release-ZIP herunterladen</a>
        <a class="btn btn-outline-secondary" href="<?= $safe((string) $update['release_url']) ?>" target="_blank" rel="noopener noreferrer">Änderungen ansehen</a></p>
        <p>Nach Backup und Prüfung: ZIP unter Plugins → Plugin-Manager → Upload einspielen und das JTL-Update im Backend ausführen.</p>
    <?php else: ?>
        <p>Installierte Version <?= $safe($version) ?> ist aktuell.</p>
    <?php endif; ?>
</div></div>
