<?php

declare(strict_types=1);

namespace App\Domain\OutputTree\StateMachine\State;

use App\Domain\OutputTree\Node;

class EndEmptyChildrenStringState implements State
{
    public function __construct(
        private Node $node,
        private int $level,
    ) {
    }

    public function getStrings(): array
    {
        return [']' . PHP_EOL];
    }

    public function next(): ?State
    {
        $level = $this->level - 1;
        return $this->node->isLastSibling()
            ? new LastElementEndState($this->node, $level)
            : new ElementEndState($this->node, $level);
    }
}
