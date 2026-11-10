<?php declare(strict_types=1);

$version = '1.0.2';
$safe = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<div class="card"><div class="card-body">
    <h2>Systemstatus</h2>
    <dl>
        <dt>Plugin-Version</dt><dd><?= $safe($version) ?></dd>
        <dt>Unterstützte JTL-Shop-Versionen</dt><dd>5.5 bis aktuelle 5.x-Version</dd>
        <dt>Frontend-Technik</dt><dd>Serverseitiger JTL-Output-Filter mit stabilen NOVA-Ankern</dd>
        <dt>Datenschutz</dt><dd>Keine Cookies oder Tracking; Updates laufen über den Bereich „Updates“</dd>
    </dl>
    <p>Updates findest du im Menüpunkt „Updates“.</p>
</div></div>
