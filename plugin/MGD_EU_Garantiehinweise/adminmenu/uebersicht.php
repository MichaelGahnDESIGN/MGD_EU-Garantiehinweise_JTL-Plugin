<?php

declare(strict_types=1);

use JTL\Plugin\PluginInterface;
use Plugin\MGD_EU_Garantiehinweise\Admin\KitBridge;
use Plugin\MGD_EU_Garantiehinweise\Kit\Platform\Jtl\Setup;

// Tab Übersicht (Baukasten)
if (!defined('PFAD_ROOT') || !isset($oPlugin) || !$oPlugin instanceof PluginInterface) {
    http_response_code(403);
    return;
}
require_once dirname(__DIR__) . '/src/Admin/KitBridge.php';
echo Setup::renderTab(KitBridge::kit($oPlugin), 'Übersicht');
