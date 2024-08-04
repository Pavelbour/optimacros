<?php

declare(strict_types=1);

namespace App\Domain\OutputTree;

class Node
{
    public function __construct(
        private string $itemName,
        private ?Node $parent = null,
        private array $children = [],
    ) {
    }

    public function getItemName(): string
    {
        return $this->itemName;
    }

    public function addParent(Node $parentNode): void
    {
        $this->parent = $parentNode;
    }

    public function getParent(): ?Node
    {
        return $this->parent;
    }

    public function addChild(Node $childNode): void
    {
        $this->children[] = $childNode;
    }

    public function getChildren(): array
    {
        return $this->children;
    }
}
