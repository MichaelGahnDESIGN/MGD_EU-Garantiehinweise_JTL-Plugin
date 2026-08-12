<?php declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use Plugin\MGD_EU_Garantiehinweise\Language\LanguageService;

$dienst = new LanguageService('de');
assert($dienst->resolve('de-DE') === 'de');
assert($dienst->resolve('en-GB') === 'en');
assert($dienst->resolve('fr-FR') === 'de');
assert((new LanguageService('en'))->resolve('it') === 'en');

