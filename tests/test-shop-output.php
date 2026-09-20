<?php declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/fixtures/TestDocument.php';
use Plugin\MGD_EU_Garantiehinweise\Configuration\Settings;
use Plugin\MGD_EU_Garantiehinweise\Frontend\ShopOutput;

$output = new ShopOutput();
$smarty = new class { public array $article = []; public function getTemplateVars(string $key): array { return $this->article; } };
$pluginDirectory = dirname(__DIR__) . '/plugin/MGD_EU_Garantiehinweise';
$render = static function (LabelTestDocument $document, Settings $settings, string $root = '') use ($output, $smarty, $pluginDirectory): void {
    $output->render($document, $smarty, $settings, 'en-GB', $pluginDirectory, '/plugin/', $root, 'https://example.org/shop');
};
foreach (['checkout' => 'complete-order-button', 'cart' => 'cart-checkout-btn', 'product' => 'add-to-cart'] as $context => $id) {
    $document = new LabelTestDocument('<button id="' . $id . '">Kaufen</button>');
    $render($document, new Settings()); $render($document, new Settings());
    assert($document->find('.mgd-eu-notice')->length === 1);
    assert($document->find('#mgd-eu-guarantee-styles')->length === 1);
    assert($document->find('#mgd-eu-guarantee-dialog-script')->length === 0);
    assert(str_contains($document->html(), '/assets/eu/en/legal-guarantee-notice.svg'));
    $html = $document->html();
    assert((strpos($html, 'class="mgd-eu-notice ') < strpos($html, 'id="' . $id . '"')) === ($context !== 'product'));
    $disabled = new LabelTestDocument('<button id="' . $id . '"></button>');
    $render($disabled, new Settings(['notice_' . $context => 'N']));
    assert($disabled->find('.mgd-eu-notice')->length === 0);
}
$custom = new LabelTestDocument('<button class="final-order"></button>');
$render($custom, new Settings(['checkout_anchor' => '.final-order']));
assert($custom->find('.mgd-eu-notice')->length === 1);
$missing = new LabelTestDocument('<p>Andere Seite</p>'); $render($missing, new Settings());
assert($missing->find('#mgd-eu-guarantee-styles')->length === 0);

// GARAN bleibt bei deaktiviertem Hinweis nutzbar; alte Daten/defekte Dateien erzeugen kein Label.
$root = sys_get_temp_dir() . '/mgd-output-' . bin2hex(random_bytes(8));
mkdir($root . '/mediafiles/mgd-garan', 0700, true);
$file = $root . '/mediafiles/mgd-garan/test.png';
file_put_contents($file, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aWZkAAAAASUVORK5CYII='));
try {
    foreach (['aktiv', 'hersteller', 'haltbarkeit', 'kostenlos', 'ganzes_produkt', 'information_erhalten', 'label_geprueft'] as $key) {
        $smarty->article['mgd_garan_' . $key] = '1';
    }
    $smarty->article += ['mgd_garan_jahre' => '3', 'mgd_garan_marke' => 'Testmarke', 'mgd_garan_modell' => 'Testmodell',
        'mgd_garan_bedingungen_url' => 'https://example.org/terms', 'mgd_garan_label_datei' => 'test.png', 'mgd_garan_label_sha256' => hash_file('sha256', $file)];
    $document = new LabelTestDocument('<div id="image_wrapper"></div><button id="add-to-cart"></button>');
    $settings = new Settings(['notice_enabled' => 'N', 'garan_display' => 'dialog']);
    $render($document, $settings, $root); $render($document, $settings, $root);
    assert($document->find('.mgd-garan')->length === 1);
    assert($document->find('.mgd-eu-notice')->length === 0);
    assert($document->find('#mgd-eu-guarantee-dialog-script')->length === 1);
    assert(str_contains($document->html(), 'https://example.org/shop/mediafiles/mgd-garan/test.png'));
    assert(!str_contains($document->html(), 'garan-label-colour.svg'));
    file_put_contents($file, 'defekt');
    $invalid = new LabelTestDocument('<button id="add-to-cart"></button>'); $render($invalid, new Settings(), $root);
    assert($invalid->find('.mgd-garan')->length === 0);
    assert($invalid->find('.mgd-eu-notice')->length === 1);
} finally {
    unlink($file); rmdir($root . '/mediafiles/mgd-garan'); rmdir($root . '/mediafiles'); rmdir($root);
}
