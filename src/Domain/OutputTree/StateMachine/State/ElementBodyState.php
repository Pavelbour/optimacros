<?php

declare(strict_types=1);

namespace App\Domain\OutputTree\StateMachine\State;

use App\Domain\OutputTree\Node;

class ElementBodyState implements State
{
    use BaseState;

    public function __construct(
        private Node $node,
        private int $level,
    ) {
    }

    public function getStrings(): array
    {
        return $this->generateStrings();
    }

    public function next(): ?State
    {
        return $this->node->hasChildren()
            ? new EndChildrenStringState($this->node, $this->level)
            : new EndEmptyChildrenStringState($this->node, $this->level);
    }
}
