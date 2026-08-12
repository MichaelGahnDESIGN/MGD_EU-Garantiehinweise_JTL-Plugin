<?php declare(strict_types=1);

/**
 * Minimaler PSR-4-Autoloader für die JTL-unabhängigen Unit-Tests.
 */
spl_autoload_register(static function (string $klasse): void {
    $praefix = 'Plugin\\MGD_EU_Garantiehinweise\\';
    if (!str_starts_with($klasse, $praefix)) {
        return;
    }

    $relativ = substr($klasse, strlen($praefix));
    $datei   = __DIR__ . '/../plugin/MGD_EU_Garantiehinweise/src/'
        . str_replace('\\', '/', $relativ) . '.php';

    if (is_file($datei)) {
        require_once $datei;
    }
});

