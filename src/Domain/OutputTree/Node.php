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

    public function hasChildren(): bool
    {
        return sizeof($this->children) > 0;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function getNextSibling(): ?Node
    {
        return $this->isLastSibling()
            ? null
            : $this->fetchNextSibling();
        
    }

    private function fetchNextSibling(): Node
    {
        $siblings = $this->getParent()->getChildren();
        $index = array_search($this, $siblings) + 1;

        return $siblings[$index];
    }

    public function isLastSibling(): bool
    {
        if (!$this->hasSiblings()) {
            return true;
        }

        $children = $this->parent->getChildren();
        return array_search($this, $children) === sizeof($children) - 1;
    }

    private function hasSiblings(): bool
    {
        if (!$this->parent) {
            return false;
        }

        return sizeof($this->parent->getChildren()) > 1;
    }
}
