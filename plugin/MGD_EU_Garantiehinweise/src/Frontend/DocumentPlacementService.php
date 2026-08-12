<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Frontend;

/** Fügt HTML idempotent an stabile NOVA-Elemente ein. */
final class DocumentPlacementService
{
    public function place(object $document, string $legalNotice, string $garanLabel = ''): void
    {
        if ($document->find('.mgd-eu-notice')->length === 0) {
            $this->placeLegalNotice($document, $legalNotice);
        }
        if ($garanLabel !== '' && $document->find('.mgd-garan')->length === 0) {
            $gallery = $document->find('#image_wrapper')->first();
            if ($gallery->length > 0) {
                $gallery->after($garanLabel);
            } else {
                $document->find('#add-to-cart')->first()->before($garanLabel);
            }
        }
    }

    private function placeLegalNotice(object $document, string $html): void
    {
        foreach (['#complete-order-button', '#cart-checkout-btn'] as $selector) {
            $target = $document->find($selector)->first();
            if ($target->length > 0) {
                $target->before($html);
                return;
            }
        }
        $buyBox = $document->find('#add-to-cart')->first();
        if ($buyBox->length > 0) {
            $buyBox->after($html);
        }
    }
}
