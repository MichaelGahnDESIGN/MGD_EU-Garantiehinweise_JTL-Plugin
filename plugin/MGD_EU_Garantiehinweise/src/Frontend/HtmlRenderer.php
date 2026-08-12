<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Frontend;

use Plugin\MGD_EU_Garantiehinweise\Garan\GaranEligibilityResult;

/** Erzeugt sichere, vom Shop-Template unabhängige Frontend-Bausteine. */
final class HtmlRenderer
{
    public function legalNotice(string $language, string $assetUrl, string $context): string
    {
        $english = $language === 'en';
        $title = $english ? 'Your statutory guarantee rights' : 'Ihre gesetzlichen Gewährleistungsrechte';
        $text = $english
            ? 'You have statutory rights if goods are defective. These rights apply for at least two years and are not limited by a commercial guarantee.'
            : 'Bei mangelhafter Ware stehen Ihnen gesetzliche Rechte zu. Diese gelten mindestens zwei Jahre und werden durch eine freiwillige Garantie nicht eingeschränkt.';
        $alt = $english
            ? 'Official EU notice about the statutory legal guarantee'
            : 'Offizieller EU-Hinweis zur gesetzlichen Gewährleistung';
        $id = 'mgd-eu-notice-title-' . $this->escape($context);

        return '<section class="mgd-eu-notice mgd-eu-notice--' . $this->escape($context) . '" aria-labelledby="' . $id . '">' .
            '<img class="mgd-eu-notice__image" src="' . $this->escape($assetUrl) . '" alt="' . $this->escape($alt) . '">' .
            '<div class="mgd-eu-notice__copy"><strong id="' . $id . '">' . $this->escape($title) . '</strong>' .
            '<p>' . $this->escape($text) . '</p></div>' .
            '</section>';
    }

    public function garanLabel(
        string $language,
        string $compactAssetUrl,
        string $fullAssetUrl,
        GaranEligibilityResult $result
    ): string
    {
        $data = $result->data();
        $english = $language === 'en';
        $title = $english ? 'Commercial durability guarantee' : 'Gewerbliche Haltbarkeitsgarantie';
        $years = $this->formatYears((float)$data['duration'], $language);

        return '<section class="mgd-garan" aria-labelledby="mgd-garan-title">' .
            '<button class="mgd-garan__trigger" type="button" data-mgd-dialog-open="mgd-garan-dialog" aria-controls="mgd-garan-dialog" aria-expanded="false" aria-haspopup="dialog">' .
            '<img src="' . $this->escape($compactAssetUrl) . '" alt="" loading="lazy"><span><strong id="mgd-garan-title">' . $this->escape($title) . '</strong><small>' . $this->escape($years) . '</small></span></button>' .
            '<dialog class="mgd-garan__dialog" id="mgd-garan-dialog"><form method="dialog"><button class="mgd-garan__close" aria-label="' . ($english ? 'Close' : 'Schließen') . '">×</button></form>' .
            '<img src="' . $this->escape($fullAssetUrl) . '" alt="' . $this->escape($title) . '"><h2>' . $this->escape($title) . '</h2>' .
            '<dl><div><dt>' . ($english ? 'Duration' : 'Dauer') . '</dt><dd>' . $this->escape($years) . '</dd></div>' .
            '<div><dt>' . ($english ? 'Brand' : 'Marke') . '</dt><dd>' . $this->escape((string)$data['brand']) . '</dd></div>' .
            '<div><dt>' . ($english ? 'Model' : 'Modell') . '</dt><dd>' . $this->escape((string)$data['model']) . '</dd></div></dl>' .
            '<a class="btn btn-primary" href="' . $this->escape((string)$data['terms_url']) . '" target="_blank" rel="noopener noreferrer">' .
            ($english ? 'Read guarantee terms' : 'Garantiebedingungen lesen') . '</a></dialog></section>';
    }

    private function formatYears(float $years, string $language): string
    {
        $number = floor($years) === $years ? (string)(int)$years : number_format($years, 1, ',', '.');
        return $number . ($language === 'en' ? ' years' : ' Jahre');
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
