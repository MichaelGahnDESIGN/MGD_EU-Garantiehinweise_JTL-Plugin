<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Security;

/** Prüft ein unverändertes Herstellerlabel; lädt weder URLs noch ausführbare Dateiformate. */
final class LocalLabelValidator
{
    public function __construct(private readonly string $shopRoot)
    {
    }

    public function isValid(string $filename, string $expectedHash): bool
    {
        if (preg_match('/^[a-zA-Z0-9][a-zA-Z0-9_-]{0,119}\.(?:png|jpg|jpeg)$/D', $filename) !== 1
            || preg_match('/^[a-f0-9]{64}$/D', $expectedHash) !== 1) {
            return false;
        }
        $root = realpath($this->shopRoot);
        $directory = realpath($this->shopRoot . '/mediafiles/mgd-garan');
        if ($root === false || $directory === false || !str_starts_with($directory, $root . DIRECTORY_SEPARATOR)) {
            return false;
        }
        $file = realpath($directory . '/' . $filename);
        if ($file === false || !str_starts_with($file, $directory . DIRECTORY_SEPARATOR)
            || !is_file($file) || !is_readable($file)) {
            return false;
        }
        $size = filesize($file);
        if ($size === false || $size < 1 || $size > 10 * 1024 * 1024) {
            return false;
        }
        $image = @getimagesize($file);
        $expectedType = str_ends_with($filename, '.png') ? IMAGETYPE_PNG : IMAGETYPE_JPEG;
        if ($image === false || $image[2] !== $expectedType || $image[0] * $image[1] > 40000000) {
            return false;
        }
        $hash = hash_file('sha256', $file);
        return is_string($hash) && hash_equals($expectedHash, $hash);
    }
}
