<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise;

use JTL\Events\Dispatcher;
use JTL\Plugin\Bootstrapper;
use JTL\Shop;
use Plugin\MGD_EU_Garantiehinweise\Admin\KitBridge;
use Plugin\MGD_EU_Garantiehinweise\Assets\AssetIntegrityService;
use Plugin\MGD_EU_Garantiehinweise\Frontend\AssetPlacementService;
use Plugin\MGD_EU_Garantiehinweise\Frontend\DocumentPlacementService;
use Plugin\MGD_EU_Garantiehinweise\Frontend\HtmlRenderer;
use Plugin\MGD_EU_Garantiehinweise\Garan\GaranAttributeReader;
use Plugin\MGD_EU_Garantiehinweise\Garan\GaranEligibilityService;
use Plugin\MGD_EU_Garantiehinweise\Kit\Platform\Jtl\JtlKit;
use Plugin\MGD_EU_Garantiehinweise\Language\LanguageService;

/**
 * Zentraler Einstiegspunkt des Plugins.
 *
 * Der Bootstrap verbindet ausschließlich JTL mit den kleinen Fachdiensten.
 * Darstellung, Validierung und Platzierung bleiben separat testbar.
 */
final class Bootstrap extends Bootstrapper
{
    public function boot(Dispatcher $dispatcher): void
    {
        parent::boot($dispatcher);
        $this->registerPluginAutoloader();

        // Baukasten Meldung: max 1x am Tag, nie fuer Shop-Besucher
        if (!Shop::isFrontend() && PHP_SAPI !== 'cli') {
            $this->kit()?->ping();
        }

        $dispatcher->hookInto(\HOOK_SMARTY_OUTPUTFILTER, function (array $args): void {
            $document = $args['document'] ?? null;
            $smarty = $args['smarty'] ?? null;
            if (!is_object($document) || !is_object($smarty)) {
                return;
            }

            $context = $this->detectContext($document);
            if ($context === null) {
                return;
            }

            $language = (new LanguageService('de'))->resolve(Shop::getLanguageCode());
            $baseUrl = $this->getPlugin()->getPaths()->getBaseURL();
            (new AssetPlacementService())->place(
                $document,
                $baseUrl . 'frontend/css/frontend.css',
                $baseUrl . 'frontend/js/dialog.js'
            );

            $assetDirectory = __DIR__ . '/assets/eu';
            $integrity = new AssetIntegrityService($assetDirectory);
            $noticePath = $language . '/legal-guarantee-notice.svg';
            if (!$integrity->isValid($noticePath)) {
                return;
            }
            $renderer = new HtmlRenderer();
            $notice = $renderer->legalNotice(
                $language,
                $baseUrl . 'assets/eu/' . $noticePath,
                $context
            );
            $garan = '';

            if ($context === 'product') {
                $article = $smarty->getTemplateVars('Artikel');
                if (is_object($article) || is_array($article)) {
                    $result = (new GaranEligibilityService())->evaluate(
                        (new GaranAttributeReader())->read($article)
                    );
                    if (
                        $result->isEligible()
                        && $integrity->isValid('garan/garan-label-nested-display.svg')
                        && $integrity->isValid('garan/garan-label-colour.svg')
                    ) {
                        $garan = $renderer->garanLabel(
                            $language,
                            $baseUrl . 'assets/eu/garan/garan-label-nested-display.svg',
                            $baseUrl . 'assets/eu/garan/garan-label-colour.svg',
                            $result
                        );
                    }
                }
            }

            (new DocumentPlacementService())->place($document, $notice, $garan);
        }, 20);
    }

    public function installed()
    {
        parent::installed();
        $this->kit()?->ping(true);
    }

    public function enabled()
    {
        parent::enabled();
        $this->kit()?->ping(true);
    }

    public function updated($oldVersion, $newVersion)
    {
        parent::updated($oldVersion, $newVersion);
        $this->kit()?->ping(true);
    }

    // beim Deinstallieren auch bei theme.michael-gahn.de abmelden
    public function uninstalled(bool $deleteData = true): void
    {
        $this->kit()?->uninstall($deleteData);
        parent::uninstalled($deleteData);
    }

    // Baukasten holen - nie Exception, null = tut einfach nix
    private function kit(): ?JtlKit
    {
        try {
            $this->registerPluginAutoloader();
            require_once __DIR__ . '/src/Admin/KitBridge.php';

            return KitBridge::kit($this->getPlugin());
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * JTL ordnet den Plugin-Namespace standardmäßig dem Plugin-Hauptordner zu.
     * Unsere Fachdienste liegen bewusst gesammelt in src; dieser kleine Loader
     * hält diese klare Struktur auch ohne zusätzlichen Composer-Build lauffähig.
     */
    private function registerPluginAutoloader(): void
    {
        // nur 1x registrieren (boot + installed)
        static $done = false;
        if ($done) {
            return;
        }
        $done = true;
        spl_autoload_register(static function (string $class): void {
            $prefix = __NAMESPACE__ . '\\';
            if (!str_starts_with($class, $prefix)) {
                return;
            }

            $relative = substr($class, strlen($prefix));
            $file = __DIR__ . '/src/' . str_replace('\\', '/', $relative) . '.php';
            if (is_file($file)) {
                require_once $file;
            }
        });
    }

    private function detectContext(object $document): ?string
    {
        if ($document->find('#complete-order-button')->length > 0) {
            return 'checkout';
        }
        if ($document->find('#cart-checkout-btn')->length > 0) {
            return 'cart';
        }
        if ($document->find('#add-to-cart')->length > 0) {
            return 'product';
        }

        return null;
    }
}
