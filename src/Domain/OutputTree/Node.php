<?php

declare(strict_types=1);

namespace App\Domain\OutputTree;

class Node
{
    public function __construct(
        private string $itemName,
        private ?Node $parent = null,
        private ?Node $child = null,
    ) {
    }

    public function getItemName(): string
    {
        return $this->itemName;
    }

    public function getParent(): ?Node
    {
        return $this->parent;
    }

    public function getChild(): ?Node
    {
        return $this->child;
    }
}
