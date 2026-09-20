<?php declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
use Plugin\MGD_EU_Garantiehinweise\Admin\SystemStatus;
use Plugin\MGD_EU_Garantiehinweise\Configuration\Settings;
$status = (new SystemStatus())->inspect(new Settings(['notice_enabled' => 'N']), dirname(__DIR__) . '/plugin/MGD_EU_Garantiehinweise');
assert(str_contains($status['Allgemeiner Hinweis'], 'Deaktiviert'));
assert($status['Dateiprüfung: de/legal-guarantee-notice.svg'] === 'Prüfsumme stimmt');
assert(str_contains($status['Shop-Abnahme'], 'nicht automatisch'));
