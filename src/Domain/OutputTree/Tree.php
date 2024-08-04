<?php

declare(strict_types=1);

namespace App\Domain\OutputTree;

class Tree
{
    public function __construct(
        private $tree = [],
    ) {     
    }

    public function addNode(Node $node): void
    {
        $this->tree[$node->getItemName()] = $node;
    }

    public function getNode(string $itemName): ?Node
    {
        if (key_exists($itemName, $this->tree)) {
            return $this->tree[$itemName];
        }

        return null;
    }

    public function saveToJsonFile(): void
    {
        
    }
}
