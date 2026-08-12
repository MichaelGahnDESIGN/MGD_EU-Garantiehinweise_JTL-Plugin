<?php declare(strict_types=1);

/**
 * Kleiner, abhängigkeitsfreier Testläufer für lokale Entwicklung und CI.
 */
$tests = glob(__DIR__ . '/test-*.php') ?: [];
sort($tests);

foreach ($tests as $test) {
    require $test;
    fwrite(STDOUT, sprintf("OK  %s\n", basename($test)));
}
