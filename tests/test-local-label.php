<?php declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
use Plugin\MGD_EU_Garantiehinweise\Security\LocalLabelValidator;

// Synthetisches Einpixelbild testet nur Dateisicherheit, nicht optische Labelqualität.
$root = sys_get_temp_dir() . '/mgd-label-' . bin2hex(random_bytes(8));
mkdir($root . '/mediafiles/mgd-garan', 0700, true);
$file = $root . '/mediafiles/mgd-garan/test.png';
file_put_contents($file, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aWZkAAAAASUVORK5CYII='));
try {
    $validator = new LocalLabelValidator($root);
    $hash = hash_file('sha256', $file);
    assert($validator->isValid('test.png', $hash));
    assert(!$validator->isValid('../test.png', $hash));
    assert(!$validator->isValid('test.png', str_repeat('0', 64)));
    assert(!$validator->isValid('missing.png', $hash));
    file_put_contents($root . '/outside.png', file_get_contents($file));
    symlink($root . '/outside.png', $root . '/mediafiles/mgd-garan/link.png');
    assert(!$validator->isValid('link.png', $hash));
    file_put_contents($file, '<script>alert(1)</script>');
    assert(!$validator->isValid('test.png', hash_file('sha256', $file)));
} finally {
    unlink($root . '/mediafiles/mgd-garan/link.png');
    unlink($root . '/outside.png'); unlink($file);
    rmdir($root . '/mediafiles/mgd-garan'); rmdir($root . '/mediafiles'); rmdir($root);
}
