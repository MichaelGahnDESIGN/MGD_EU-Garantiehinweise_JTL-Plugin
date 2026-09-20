<?php declare(strict_types=1);
namespace Plugin\MGD_EU_Garantiehinweise\Frontend;

use Plugin\MGD_EU_Garantiehinweise\Assets\AssetIntegrityService;
use Plugin\MGD_EU_Garantiehinweise\Configuration\Settings;
use Plugin\MGD_EU_Garantiehinweise\Garan\GaranAttributeReader;
use Plugin\MGD_EU_Garantiehinweise\Garan\GaranEligibilityService;
use Plugin\MGD_EU_Garantiehinweise\Security\LocalLabelValidator;

/** Verbindet Anzeige und Datenprüfung; beide Labeltypen sind unabhängig schalt- und prüfbar. */
final class ShopOutput
{
    public function render(object $document, object $smarty, Settings $settings, string $languageCode,
        string $pluginDirectory, string $pluginUrl, string $shopRoot, string $shopUrl): void
    {
        $context = (new ContextDetector())->detect($document, $settings);
        if ($context === null) {
            return;
        }
        $language = $settings->language($languageCode);
        $integrity = new AssetIntegrityService($pluginDirectory . '/assets/eu');
        $renderer = new HtmlRenderer();
        $notice = '';
        $garan = '';
        $noticePath = $language . '/legal-guarantee-notice.svg';
        if ($settings->noticeEnabled($context) && $integrity->isValid($noticePath)) {
            $notice = $renderer->legalNotice($language, $pluginUrl . 'assets/eu/' . $noticePath, $context, $settings);
        }
        if ($context === 'product' && $settings->get('garan_enabled') === 'Y') {
            $article = $smarty->getTemplateVars('Artikel');
            if (is_object($article) || is_array($article)) {
                $result = (new GaranEligibilityService())->evaluate(
                    (new GaranAttributeReader())->read($article, $settings->get('attribute_prefix'))
                );
                $data = $result->data();
                if ($result->isEligible() && $shopRoot !== ''
                    && (new LocalLabelValidator($shopRoot))->isValid($data['label_file'], $data['label_hash'])
                    && ($settings->get('garan_display') === 'inline' || $integrity->isValid('garan/garan-label-nested-display.svg'))) {
                    $garan = $renderer->garanLabel($language,
                        $pluginUrl . 'assets/eu/garan/garan-label-nested-display.svg',
                        rtrim($shopUrl, '/') . '/mediafiles/mgd-garan/' . rawurlencode($data['label_file']), $result, $settings);
                }
            }
        }
        if ($notice === '' && $garan === '') {
            return;
        }
        (new AssetPlacementService())->place($document, $pluginUrl . 'frontend/css/frontend.css',
            $garan !== '' && $settings->get('garan_display') === 'dialog' ? $pluginUrl . 'frontend/js/dialog.js' : '');
        (new DocumentPlacementService())->place($document, $notice, $garan, $settings);
    }
}
