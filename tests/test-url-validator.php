<?php declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use Plugin\MGD_EU_Garantiehinweise\Security\ExternalUrlValidator;

$dienst = new ExternalUrlValidator();
assert($dienst->isValid('https://example.org/garantie'));
assert(!$dienst->isValid('http://example.org/garantie'));
assert(!$dienst->isValid('/garantie'));
assert(!$dienst->isValid('https://name:pass@example.org/garantie'));
assert(!$dienst->isValid('javascript:alert(1)'));

