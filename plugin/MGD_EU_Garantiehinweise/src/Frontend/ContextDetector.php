<?php declare(strict_types=1);
namespace Plugin\MGD_EU_Garantiehinweise\Frontend;
use Plugin\MGD_EU_Garantiehinweise\Configuration\Settings;

/** Erkennt NOVA oder konfigurierte Template-Anker; Checkout hat vor Warenkorb/Produkt Vorrang. */
final class ContextDetector
{
    public function detect(object $document, Settings $settings): ?string
    {
        foreach (['checkout', 'cart', 'product'] as $context) {
            if ($document->find($settings->get($context . '_anchor'))->length > 0) {
                return $context;
            }
        }
        return null;
    }
}
