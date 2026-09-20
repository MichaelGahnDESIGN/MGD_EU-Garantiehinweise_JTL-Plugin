<?php declare(strict_types=1);
/** Anzeige nur im JTL-Kontext; JTL schützt den Zugang zu seinen Plugin-Adminseiten. */
if (!defined('PFAD_ROOT')) { return; }
use Plugin\MGD_EU_Garantiehinweise\Configuration\Settings;
use Plugin\MGD_EU_Garantiehinweise\Admin\SystemStatus;
$settings = isset($plugin) && is_object($plugin) ? Settings::fromPlugin($plugin) : new Settings();
$status = (new SystemStatus())->inspect($settings, dirname(__DIR__));
$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<div class="card"><div class="card-body">
    <h2>Technischer Systemstatus – Version 1.1.0</h2>
    <?php if (!isset($plugin)): ?><p>Keine Plugininstanz verfügbar: Es werden nur Standardwerte angezeigt.</p><?php endif; ?>
    <dl><?php foreach ($status as $name => $value): ?><dt><?= $escape($name) ?></dt><dd><?= $escape($value) ?></dd><?php endforeach; ?></dl>
    <p>Prüfsummen und aktivierte Einstellungen bestätigen keine rechtliche Zulässigkeit oder tatsächlich sichtbare Platzierung. Keine Cookies, kein Tracking, keine externen Laufzeitaufrufe durch das Plugin.</p>
</div></div>
