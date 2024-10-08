<?php

declare(strict_types=1);

namespace App\Domain\InputTree;

class Tree
{
    private const array NODE_TYPES = [
        'Изделия и компоненты' => 'articleComponentIndex',
        'Прямые компоненты' => 'directComponentIndex',
        'Варианты комплектации' => 'variantIndex',
    ];

    private $tree = [];

    private $index = [
        'directComponentIndex' => [],
        'articleComponentIndex' => [],
        'variantIndex' => [],
    ];

    public function getSize(): int
    {
        return sizeof($this->tree);
    }

    public function addNode(Node $node): void
    {
        $this->tree[$node->getItemName()] = $node;
        $this->addToIndex($node);
    }

    private function addToIndex(Node $node): void
    {
        if (key_exists($node->getType(), self::NODE_TYPES)) {
            $key = self::NODE_TYPES[$node->getType()];
            $this->index[$key][] = $node;
        }
    }

    public function getNodes(): array
    {
        return $this->tree;
    }

    public function getNode(string $itemname): ?Node
    {
        return $this->tree[$itemname] ?? null;
    }

    public function getIndex(): array
    {
        return $this->index;
    }
}
