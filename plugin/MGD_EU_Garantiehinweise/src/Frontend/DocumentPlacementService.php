<?php declare(strict_types=1);
namespace Plugin\MGD_EU_Garantiehinweise\Frontend;
use Plugin\MGD_EU_Garantiehinweise\Configuration\Settings;

/** Fügt beide Kennzeichnungen unabhängig und pro Dokument höchstens einmal ein. */
final class DocumentPlacementService
{
    public function place(object $document, string $legalNotice, string $garanLabel = '', ?Settings $settings = null): void
    {
        $settings ??= new Settings();
        $context = (new ContextDetector())->detect($document, $settings);
        if ($context === null) {
            return;
        }
        if ($legalNotice !== '' && $settings->noticeEnabled($context) && $document->find('.mgd-eu-notice')->length === 0) {
            $target = $document->find($settings->get($context . '_anchor'))->first();
            if ($context === 'product') {
                $target->after($legalNotice);
            } else {
                $target->before($legalNotice);
            }
        }
        if ($context === 'product' && $settings->get('garan_enabled') === 'Y'
            && $garanLabel !== '' && $document->find('.mgd-garan')->length === 0) {
            $gallery = $document->find($settings->get('gallery_anchor'))->first();
            if ($settings->get('garan_position') === 'gallery' && $gallery->length > 0) {
                $gallery->after($garanLabel);
            } else {
                $document->find($settings->get('product_anchor'))->first()->before($garanLabel);
            }
        }
    }
}
