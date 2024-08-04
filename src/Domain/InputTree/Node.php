<?php

declare(strict_types=1);

namespace App\Domain\InputTree;

class Node
{
    private string $itemName;
    private string $type;
    private string $parent;
    private string $relation;

    public function __construct(
        string $itemName,
        string $type,
        string $parent = '',
        string $relation = '',
    )
    {
        $this->itemName = $itemName;
        $this->type = $type;
        $this->parent = $parent;
        $this->relation = $relation;
    }

    public function getItemName(): string
    {
        return $this->itemName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getParent(): string
    {
        return $this->parent;
    }

    public function getRelation(): string
    {
        return $this->relation;
    }
}
