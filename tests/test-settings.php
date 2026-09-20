<?php declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
use Plugin\MGD_EU_Garantiehinweise\Configuration\Settings;

$settings = new Settings(['notice_width' => '1;display:none', 'checkout_anchor' => 'body script', 'notice_cart' => 'N']);
assert($settings->get('notice_width') === '800');
assert($settings->get('checkout_anchor') === '#complete-order-button');
assert(!$settings->noticeEnabled('cart'));
assert($settings->noticeEnabled('checkout'));
assert(!$settings->noticeEnabled('unknown'));
assert((new Settings(['language' => 'en']))->language('de-DE') === 'en');
assert((new Settings(['fallback_language' => 'en']))->language('fr') === 'en');
assert((new Settings(['attribute_prefix' => '../']))->get('attribute_prefix') === 'mgd_garan_');
assert((new Settings(['notice_enabled' => []]))->get('notice_enabled') === 'Y');
$xml = simplexml_load_file(__DIR__ . '/../plugin/MGD_EU_Garantiehinweise/info.xml');
$definitions = Settings::definitions();
$found = [];
foreach ($xml->Install->Adminmenu->Settingslink as $tab) {
    foreach ($tab->Setting as $field) {
        $key = substr((string)$field->ValueName, strlen('mgd_eu_'));
        assert(isset($definitions[$key]), $key);
        assert((string)$field['initialValue'] === $definitions[$key]['default'], $key);
        $options = [];
        foreach ($field->SelectboxOptions->Option ?? [] as $option) {
            $options[(string)$option['value']] = (string)$option;
        }
        assert($options === $definitions[$key]['options'], $key);
        $found[] = $key;
    }
}
assert(count($found) === count($definitions));

$plugin = new class {
    public function getConfig(): object {
        return new class { public function getValue(string $key): ?string { return $key === 'mgd_eu_language' ? 'en' : null; } };
    }
};
assert(Settings::fromPlugin($plugin)->language('de') === 'en');
