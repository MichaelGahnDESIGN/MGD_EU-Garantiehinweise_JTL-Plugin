<?php declare(strict_types=1);
/** DOM-Testadapter: prüft HTML-Reihenfolge und Mehrfachaufrufe, ersetzt keinen echten phpQuery/JTL-Test. */
final class LabelTestDocument
{
    public DOMDocument $dom;
    public function __construct(string $html)
    {
        $this->dom = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $this->dom->loadHTML('<!doctype html><html><head><meta charset="utf-8"></head><body>' . $html . '</body></html>');
        libxml_clear_errors(); libxml_use_internal_errors($previous);
    }
    public function find(string $selector): LabelTestSelection
    {
        $xpath = new DOMXPath($this->dom);
        $expression = match ($selector[0]) {
            '#' => '//*[@id="' . substr($selector, 1) . '"]',
            '.' => '//*[contains(concat(" ", normalize-space(@class), " "), " ' . substr($selector, 1) . ' ")]',
            default => '//' . $selector,
        };
        return new LabelTestSelection($this, iterator_to_array($xpath->query($expression)));
    }
    public function html(): string { return $this->dom->saveHTML(); }
}
final class LabelTestSelection
{
    public int $length;
    public function __construct(private LabelTestDocument $document, private array $nodes)
    { $this->length = count($nodes); }
    public function first(): self { return new self($this->document, array_slice($this->nodes, 0, 1)); }
    public function before(string $html): void { $this->insert($html, 'before'); }
    public function after(string $html): void { $this->insert($html, 'after'); }
    public function append(string $html): void { $this->insert($html, 'append'); }
    private function insert(string $html, string $where): void
    {
        $temporary = new LabelTestDocument('<div id="fixture-wrapper">' . $html . '</div>');
        $wrapper = $temporary->dom->getElementById('fixture-wrapper');
        foreach ($this->nodes as $target) {
            $parent = $where === 'append' ? $target : $target->parentNode;
            $reference = $where === 'before' ? $target : ($where === 'after' ? $target->nextSibling : null);
            foreach (iterator_to_array($wrapper->childNodes) as $node) {
                $parent->insertBefore($this->document->dom->importNode($node, true), $reference);
            }
        }
    }
}
