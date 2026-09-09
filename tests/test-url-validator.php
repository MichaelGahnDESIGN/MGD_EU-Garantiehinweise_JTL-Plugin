<?php declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use Plugin\MGD_EU_Garantiehinweise\Security\ExternalUrlValidator;

$dienst = new ExternalUrlValidator();
assert($dienst->isValid('https://example.org/garantie'));
assert(!$dienst->isValid('http://example.org/garantie'));
assert(!$dienst->isValid('/garantie'));
assert(!$dienst->isValid('https://name:pass@example.org/garantie'));
assert(!$dienst->isValid('javascript:alert(1)'));


foreach (['https://localhost/test', 'https://127.0.0.1/test', 'https://[::1]/test', 'https://shop.local/test', 'https://example.org/terms?token=secret', 'https://example.org/terms#secret', 'https://example.org:8443/test', "https://example.org/te\nst"] as $url) {
    assert(!$dienst->isValid($url));
}
