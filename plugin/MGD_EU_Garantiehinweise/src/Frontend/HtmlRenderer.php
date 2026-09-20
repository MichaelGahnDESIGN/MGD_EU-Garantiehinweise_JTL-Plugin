<?php declare(strict_types=1);
namespace Plugin\MGD_EU_Garantiehinweise\Frontend;
use Plugin\MGD_EU_Garantiehinweise\Configuration\Settings;
use Plugin\MGD_EU_Garantiehinweise\Garan\GaranEligibilityResult;

/** Bereitet ausschließlich validierte Ansichtsparameter vor; HTML liegt in getrennten Templates. */
final class HtmlRenderer
{
    public function legalNotice(string $language, string $assetUrl, string $context, ?Settings $settings = null): string
    {
        return $this->render('notice', [
            'english' => $language === 'en', 'assetUrl' => $assetUrl, 'context' => $context,
            'settings' => $settings ?? new Settings(),
        ]);
    }

    public function garanLabel(string $language, string $compactAssetUrl, string $fullAssetUrl,
        GaranEligibilityResult $result, ?Settings $settings = null): string
    {
        if (!$result->isEligible()) {
            return '';
        }
        $data = $result->data();
        $english = $language === 'en';
        $years = rtrim(rtrim(number_format((float)$data['duration'], 1, $english ? '.' : ',', ''), '0'), $english ? '.' : ',')
            . ($english ? ' years' : ' Jahre');
        return $this->render('garan', [
            'english' => $english, 'compactAssetUrl' => $compactAssetUrl, 'fullAssetUrl' => $fullAssetUrl,
            'data' => $data, 'years' => $years, 'settings' => $settings ?? new Settings(),
        ]);
    }

    /** Templates sind feste interne Namen; Benutzereingaben dürfen niemals Dateipfade bestimmen. */
    private function render(string $template, array $variables): string
    {
        $escape = static fn(mixed $value): string => htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        extract($variables, EXTR_SKIP);
        ob_start();
        try {
            require dirname(__DIR__, 2) . '/templates/' . $template . '.php';
            return (string)ob_get_contents();
        } finally {
            ob_end_clean();
        }
    }
}
