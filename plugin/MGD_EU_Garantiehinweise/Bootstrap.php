<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise;

use JTL\Events\Dispatcher;
use JTL\Plugin\Bootstrapper;
use JTL\Shop;
use Plugin\MGD_EU_Garantiehinweise\Assets\AssetIntegrityService;
use Plugin\MGD_EU_Garantiehinweise\Frontend\AssetPlacementService;
use Plugin\MGD_EU_Garantiehinweise\Frontend\DocumentPlacementService;
use Plugin\MGD_EU_Garantiehinweise\Frontend\HtmlRenderer;
use Plugin\MGD_EU_Garantiehinweise\Garan\GaranAttributeReader;
use Plugin\MGD_EU_Garantiehinweise\Garan\GaranEligibilityService;
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

    /**
     * JTL ordnet den Plugin-Namespace standardmäßig dem Plugin-Hauptordner zu.
     * Unsere Fachdienste liegen bewusst gesammelt in src; dieser kleine Loader
     * hält diese klare Struktur auch ohne zusätzlichen Composer-Build lauffähig.
     */
    private function registerPluginAutoloader(): void
    {
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
