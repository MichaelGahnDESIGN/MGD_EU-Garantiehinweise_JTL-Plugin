<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise\Portlets\EUGarantiehinweise;

use JTL\OPC\InputType;
use JTL\OPC\Portlet;
use JTL\OPC\PortletInstance;

/**
 * Redaktionelles OPC-Portlet für zusätzliche Gewährleistungshinweise.
 */
final class EUGarantiehinweise extends Portlet
{
    /**
     * Stellt dem Template ausschließlich ein internes, geprüftes Shop-Ziel bereit.
     * Protokoll-relative und ausführbare URLs werden bewusst verworfen.
     */
    public function initInstance(PortletInstance $instance): void
    {
        $instance->safeLinkUrl = $this->normalizeInternalPath(
            (string)$instance->getProperty('link-url')
        );
    }

    public function getButtonHtml(): string
    {
        return $this->getFontAwesomeButtonHtml('fas fa-shield-alt');
    }

    /** @return array<string, array<string, mixed>> */
    public function getPropertyDesc(): array
    {
        return [
            'title' => [
                'label' => 'Überschrift',
                'type' => InputType::TEXT,
                'default' => 'Ihre gesetzlichen Gewährleistungsrechte',
            ],
            'text' => [
                'label' => 'Ergänzender Text',
                'type' => InputType::RICHTEXT,
                'default' => 'Bei mangelhafter Ware stehen Ihnen gesetzliche Rechte zu. Freiwillige Garantien schränken diese Rechte nicht ein.',
            ],
            'link-label' => [
                'label' => 'Linktext',
                'type' => InputType::TEXT,
                'default' => 'Mehr erfahren',
            ],
            'link-url' => [
                'label' => 'Interner Shop-Pfad (beginnt mit /)',
                'type' => InputType::TEXT,
                'default' => '',
            ],
        ];
    }

    private function normalizeInternalPath(string $path): string
    {
        $path = trim($path);
        if (
            $path === ''
            || !str_starts_with($path, '/')
            || str_starts_with($path, '//')
            || str_contains($path, '\\')
            || preg_match('/[\x00-\x1F\x7F]/', $path) === 1
        ) {
            return '';
        }

        return $path;
    }
}
