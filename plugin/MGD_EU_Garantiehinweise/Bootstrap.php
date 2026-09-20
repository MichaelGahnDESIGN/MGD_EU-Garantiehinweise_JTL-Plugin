<?php declare(strict_types=1);

namespace Plugin\MGD_EU_Garantiehinweise;

use JTL\Events\Dispatcher;
use JTL\Plugin\Bootstrapper;
use JTL\Shop;
use Plugin\MGD_EU_Garantiehinweise\Configuration\Settings;
use Plugin\MGD_EU_Garantiehinweise\Frontend\ShopOutput;

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

            (new ShopOutput())->render(
                $document, $smarty, Settings::fromPlugin($this->getPlugin()), Shop::getLanguageCode(),
                __DIR__, $this->getPlugin()->getPaths()->getBaseURL(),
                defined('PFAD_ROOT') ? (string)\PFAD_ROOT : '', Shop::getURL()
            );
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

}
