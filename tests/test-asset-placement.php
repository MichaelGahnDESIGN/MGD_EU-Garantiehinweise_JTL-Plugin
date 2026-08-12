<?php declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use Plugin\MGD_EU_Garantiehinweise\Frontend\AssetPlacementService;

/** Kleine Testauswahl, die nur die von phpQuery benötigte Oberfläche nachbildet. */
final class TestAssetSelection
{
    public int $length;

    /** @var list<string> */
    public array $appended = [];

    public function __construct(int $length)
    {
        $this->length = $length;
    }

    public function first(): self
    {
        return $this;
    }

    public function append(string $html): void
    {
        $this->appended[] = $html;
    }
}

final class TestAssetDocument
{
    public TestAssetSelection $head;
    public TestAssetSelection $body;

    /** @var array<string, TestAssetSelection> */
    private array $selectors;

    public function __construct(bool $assetsExist = false)
    {
        $this->head = new TestAssetSelection(1);
        $this->body = new TestAssetSelection(1);
        $this->selectors = [
            'head' => $this->head,
            'body' => $this->body,
            '#mgd-eu-guarantee-styles' => new TestAssetSelection($assetsExist ? 1 : 0),
            '#mgd-eu-guarantee-dialog-script' => new TestAssetSelection($assetsExist ? 1 : 0),
        ];
    }

    public function find(string $selector): TestAssetSelection
    {
        return $this->selectors[$selector] ?? new TestAssetSelection(0);
    }
}

$dienst = new AssetPlacementService();
$dokument = new TestAssetDocument();
$dienst->place($dokument, '/frontend.css?x="test', '/dialog.js');

assert(count($dokument->head->appended) === 1);
assert(count($dokument->body->appended) === 1);
assert(str_contains($dokument->head->appended[0], 'mgd-eu-guarantee-styles'));
assert(str_contains($dokument->head->appended[0], '&quot;test'));
assert(str_contains($dokument->body->appended[0], ' defer'));

$vorhanden = new TestAssetDocument(true);
$dienst->place($vorhanden, '/frontend.css', '/dialog.js');
assert($vorhanden->head->appended === []);
assert($vorhanden->body->appended === []);
