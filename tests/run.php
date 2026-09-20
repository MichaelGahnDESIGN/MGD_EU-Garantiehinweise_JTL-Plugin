<?php declare(strict_types=1);

/**
 * Kleiner, abhängigkeitsfreier Testläufer für lokale Entwicklung und CI.
 */
if ((int)ini_get('zend.assertions') !== 1 || !(bool)ini_get('assert.exception')) {
    throw new RuntimeException('Tests mit -d zend.assertions=1 -d assert.exception=1 starten.');
}

$tests = glob(__DIR__ . '/test-*.php') ?: [];
sort($tests);

foreach ($tests as $test) {
    require $test;
    fwrite(STDOUT, sprintf("OK  %s\n", basename($test)));
}
