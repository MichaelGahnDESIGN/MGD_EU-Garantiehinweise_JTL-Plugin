<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Frontend;

/**
 * Bindet die eigenen Frontend-Dateien des Plugins genau einmal ein.
 *
 * Die Einbindung erfolgt bewusst im bereits von JTL bereitgestellten
 * Smarty-Outputfilter. Dadurch bleibt das Plugin unabhängig von Theme-Builds
 * und von nicht in allen JTL-5-Versionen verfügbaren XML-Knoten.
 */
final class AssetPlacementService
{
    public function place(object $document, string $cssUrl, string $javascriptUrl): void
    {
        if ($document->find('#mgd-eu-guarantee-styles')->length === 0) {
            $head = $document->find('head')->first();
            if ($head->length > 0) {
                $head->append(sprintf(
                    '<link id="mgd-eu-guarantee-styles" rel="stylesheet" href="%s">',
                    $this->escapeUrl($cssUrl)
                ));
            }
        }

        if ($javascriptUrl !== '' && $document->find('#mgd-eu-guarantee-dialog-script')->length === 0) {
            $body = $document->find('body')->first();
            if ($body->length > 0) {
                $body->append(sprintf(
                    '<script id="mgd-eu-guarantee-dialog-script" src="%s" defer></script>',
                    $this->escapeUrl($javascriptUrl)
                ));
            }
        }
    }

    private function escapeUrl(string $url): string
    {
        return htmlspecialchars($url, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
